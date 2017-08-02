<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Infrastructure\SystemEvent\Listener;

use Broadway\CommandHandling\CommandBusInterface;
use Broadway\EventDispatcher\EventDispatcherInterface;
use OpenLoyalty\Component\Account\Domain\SystemEvent\AccountCreatedSystemEvent;
use OpenLoyalty\Component\Account\Domain\SystemEvent\AvailablePointsAmountChangedSystemEvent;
use OpenLoyalty\Component\Customer\Domain\Command\MoveCustomerToLevel;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\LevelId;
use OpenLoyalty\Component\Customer\Domain\LevelIdProvider;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetailsRepository;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerLevelChangedSystemEvent;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerSystemEvents;
use OpenLoyalty\Component\Customer\Domain\TransactionId;
use OpenLoyalty\Component\Level\Domain\Level;
use OpenLoyalty\Component\Level\Domain\LevelRepository;
use OpenLoyalty\Component\Transaction\Domain\SystemEvent\CustomerAssignedToTransactionSystemEvent;
use OpenLoyalty\Component\Customer\Infrastructure\ExcludeDeliveryCostsProvider;
use OpenLoyalty\Component\Customer\Infrastructure\TierAssignTypeProvider;

/**
 * Class CalculateCustomerLevelListener.
 */
class CalculateCustomerLevelListener
{
    /**
     * @var LevelIdProvider
     */
    protected $levelIdProvider;

    /**
     * @var CustomerDetailsRepository
     */
    protected $customerDetailsRepository;

    /**
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * @var TierAssignTypeProvider
     */
    protected $tierAssignTypeProvider;

    /**
     * @var ExcludeDeliveryCostsProvider
     */
    protected $excludeDeliveryCostsProvider;

    /** @var LevelRepository */
    protected $levelRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * CalculateCustomerLevelListener constructor.
     *
     * @param LevelIdProvider              $levelIdProvider
     * @param CustomerDetailsRepository    $customerDetailsRepository
     * @param CommandBusInterface          $commandBus
     * @param TierAssignTypeProvider       $tierAssignTypeProvider
     * @param ExcludeDeliveryCostsProvider $excludeDeliveryCostsProvider
     * @param LevelRepository              $levelRepository
     * @param EventDispatcherInterface     $eventDispatcher
     */
    public function __construct(
        LevelIdProvider $levelIdProvider,
        CustomerDetailsRepository $customerDetailsRepository,
        CommandBusInterface $commandBus,
        TierAssignTypeProvider $tierAssignTypeProvider,
        ExcludeDeliveryCostsProvider $excludeDeliveryCostsProvider,
        LevelRepository $levelRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->levelIdProvider = $levelIdProvider;
        $this->customerDetailsRepository = $customerDetailsRepository;
        $this->commandBus = $commandBus;
        $this->tierAssignTypeProvider = $tierAssignTypeProvider;
        $this->excludeDeliveryCostsProvider = $excludeDeliveryCostsProvider;
        $this->levelRepository = $levelRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle($event)
    {
        if ($event instanceof AccountCreatedSystemEvent) {
            $this->handleAccountCreated($event);
        } elseif ($this->tierAssignTypeProvider->getType() == TierAssignTypeProvider::TYPE_POINTS && $event instanceof AvailablePointsAmountChangedSystemEvent) {
            $this->handlePoints($event);
        } elseif ($this->tierAssignTypeProvider->getType() == TierAssignTypeProvider::TYPE_TRANSACTIONS && $event instanceof CustomerAssignedToTransactionSystemEvent) {
            $this->handleTransaction($event);
        }
    }

    protected function handleTransaction(CustomerAssignedToTransactionSystemEvent $event)
    {
        $transactionId = $event->getTransactionId();
        $customerId = $event->getCustomerId();

        /** @var CustomerDetails $customer */
        $customer = $this->customerDetailsRepository->find($customerId->__toString());

        if (!$customer) {
            return;
        }

        if ($this->excludeDeliveryCostsProvider->areExcluded()) {
            $currentAmount = $customer->getTransactionsAmountWithoutDeliveryCosts() - $customer->getAmountExcludedForLevel();
            if (!$customer->hasTransactionId(new TransactionId($transactionId->__toString()))) {
                $currentAmount += $event->getGrossValueWithoutDeliveryCosts() - $event->getAmountExcludedForLevel();
            }
        } else {
            $currentAmount = $customer->getTransactionsAmount() - $customer->getAmountExcludedForLevel();

            if (!$customer->hasTransactionId(new TransactionId($transactionId->__toString()))) {
                $currentAmount += $event->getGrossValue() - $event->getAmountExcludedForLevel();
            }
        }

        /** @var Level $currentLevel */
        $currentLevel = $customer->getLevelId()
            ? $this->levelRepository->byId(new \OpenLoyalty\Component\Level\Domain\LevelId($customer->getLevelId()->__toString()))
            : null;

        $levelId = $this->levelIdProvider->findLevelIdByConditionValueWithTheBiggestReward($currentAmount);
        if (!$levelId) {
            return;
        }
        /** @var Level $level */
        $level = $this->levelRepository->byId(new \OpenLoyalty\Component\Level\Domain\LevelId($levelId));

        // if new level is better than old one -> move customer
        if (!$currentLevel || $currentLevel->getReward()->getValue() < $level->getReward()->getValue()) {
            if (!$customer->getLevelId() || $customer->getLevelId()->__toString() != $levelId) {
                $this->commandBus->dispatch(
                    new MoveCustomerToLevel(
                        new CustomerId($customerId->__toString()),
                        new LevelId($levelId)
                    )
                );

                $this->eventDispatcher->dispatch(CustomerSystemEvents::CUSTOMER_LEVEL_CHANGED, [
                    new CustomerLevelChangedSystemEvent($customer->getCustomerId(), new LevelId($levelId)),
                ]);
            }

            return;
        }
        // new level is worst
        $newLevelId = $levelId;

        if ($customer->getManuallyAssignedLevelId()) {
            $manualId = $customer->getManuallyAssignedLevelId()->__toString();
            if ($manualId == $currentLevel->getLevelId()->__toString()) {
                return;
            }
            /** @var Level $manual */
            $manual = $this->levelRepository->byId(new \OpenLoyalty\Component\Level\Domain\LevelId($manualId));
            if ($manual->getReward()->getValue() > $level->getReward()->getValue()) {
                $newLevelId = $manualId;
            }
        }

        $this->commandBus->dispatch(
            new MoveCustomerToLevel(
                new CustomerId($customerId->__toString()),
                new LevelId($newLevelId)
            )
        );

        $this->eventDispatcher->dispatch(CustomerSystemEvents::CUSTOMER_LEVEL_CHANGED, [
            new CustomerLevelChangedSystemEvent($customer->getCustomerId(), new LevelId($newLevelId)),
        ]);
    }

    protected function handlePoints(AvailablePointsAmountChangedSystemEvent $event)
    {
        $customerId = $event->getCustomerId();
        $currentAmount = $event->getCurrentAmount();

        /** @var CustomerDetails $customer */
        $customer = $this->customerDetailsRepository->find($customerId->__toString());

        /** @var Level $currentLevel */
        $currentLevel = $customer->getLevelId()
            ? $this->levelRepository->byId(new \OpenLoyalty\Component\Level\Domain\LevelId($customer->getLevelId()->__toString()))
            : null;

        $levelId = $this->levelIdProvider->findLevelIdByConditionValueWithTheBiggestReward($currentAmount);
        if (!$levelId) {
            return;
        }

        /** @var Level $level */
        $level = $this->levelRepository->byId(new \OpenLoyalty\Component\Level\Domain\LevelId($levelId));

        if ($currentLevel && $currentLevel->getReward()->getValue() >= $level->getReward()->getValue()) {
            return;
        }

        if (!$customer->getLevelId() || $customer->getLevelId()->__toString() != $levelId) {
            $this->commandBus->dispatch(
                new MoveCustomerToLevel(
                    new CustomerId($customerId->__toString()),
                    new LevelId($levelId)
                )
            );

            $this->eventDispatcher->dispatch(CustomerSystemEvents::CUSTOMER_LEVEL_CHANGED, [
                new CustomerLevelChangedSystemEvent($customer->getCustomerId(), new LevelId($levelId)),
            ]);
        }
    }

    protected function handleAccountCreated(AccountCreatedSystemEvent $event)
    {
        $customerId = $event->getCustomerId();
        if (!$customerId) {
            return;
        }
        $currentAmount = 0;
        /** @var CustomerDetails $customer */
        $customer = $this->customerDetailsRepository->find($customerId->__toString());

        if ($customer->getLevelId()) {
            return;
        }

        $levelId = $this->levelIdProvider->findLevelIdByConditionValueWithTheBiggestReward($currentAmount);
        if (!$levelId) {
            $this->commandBus->dispatch(
                new MoveCustomerToLevel(
                    new CustomerId($customerId->__toString()),
                    null
                )
            );

            return;
        }

        if (!$customer->getLevelId() || $customer->getLevelId()->__toString() != $levelId) {
            $this->commandBus->dispatch(
                new MoveCustomerToLevel(
                    new CustomerId($customerId->__toString()),
                    new LevelId($levelId)
                )
            );
        }
    }
}
