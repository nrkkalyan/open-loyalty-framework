<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Transaction\Domain\Event\Listener;

use Broadway\CommandHandling\CommandBusInterface;
use Broadway\Domain\DomainMessage;
use Broadway\EventDispatcher\EventDispatcherInterface;
use Broadway\EventHandling\EventListenerInterface;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerSystemEvents;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerUpdatedSystemEvent;
use OpenLoyalty\Component\Transaction\Domain\Command\AssignCustomerToTransaction;
use OpenLoyalty\Component\Transaction\Domain\CustomerId;
use OpenLoyalty\Component\Transaction\Domain\CustomerIdProvider;
use OpenLoyalty\Component\Transaction\Domain\CustomerTransactionsSummaryProvider;
use OpenLoyalty\Component\Transaction\Domain\Event\TransactionWasRegistered;
use OpenLoyalty\Component\Transaction\Domain\ReadModel\TransactionDetails;
use OpenLoyalty\Component\Transaction\Domain\ReadModel\TransactionDetailsRepository;
use OpenLoyalty\Component\Transaction\Domain\SystemEvent\CustomerAssignedToTransactionSystemEvent;
use OpenLoyalty\Component\Transaction\Domain\SystemEvent\CustomerFirstTransactionSystemEvent;
use OpenLoyalty\Component\Transaction\Domain\SystemEvent\TransactionSystemEvents;
use OpenLoyalty\Component\Customer\Domain\CustomerId as ClientId;

/**
 * Class AssignCustomerToTransactionListener.
 */
class AssignCustomerToTransactionListener implements EventListenerInterface
{
    /**
     * @var CustomerIdProvider
     */
    protected $customerIdProvider;

    /**
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var TransactionDetailsRepository
     */
    protected $transactionDetailsRepository;

    /**
     * @var CustomerTransactionsSummaryProvider
     */
    protected $customerTransactionsSummaryProvider;

    /**
     * AssignCustomerToTransactionListener constructor.
     *
     * @param CustomerIdProvider                  $customerIdProvider
     * @param CommandBusInterface                 $commandBus
     * @param EventDispatcherInterface            $eventDispatcher
     * @param TransactionDetailsRepository        $transactionDetailsRepository
     * @param CustomerTransactionsSummaryProvider $customerTransactionsSummaryProvider
     */
    public function __construct(
        CustomerIdProvider $customerIdProvider,
        CommandBusInterface $commandBus,
        EventDispatcherInterface $eventDispatcher,
        TransactionDetailsRepository $transactionDetailsRepository,
        CustomerTransactionsSummaryProvider $customerTransactionsSummaryProvider
    ) {
        $this->customerIdProvider = $customerIdProvider;
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
        $this->transactionDetailsRepository = $transactionDetailsRepository;
        $this->customerTransactionsSummaryProvider = $customerTransactionsSummaryProvider;
    }

    public function onTransactionRegistered(TransactionWasRegistered $event)
    {
        $customerId = $this->customerIdProvider->getId($event->getCustomerData());
        if ($customerId) {
            $transactionsCount = $this->customerTransactionsSummaryProvider->getTransactionsCount(new CustomerId($customerId));

            $this->commandBus->dispatch(
                new AssignCustomerToTransaction($event->getTransactionId(), new CustomerId($customerId))
            );
            /** @var TransactionDetails $transaction */
            $transaction = $this->transactionDetailsRepository->find($event->getTransactionId()->__toString());
            $this->eventDispatcher->dispatch(
                TransactionSystemEvents::CUSTOMER_ASSIGNED_TO_TRANSACTION,
                [new CustomerAssignedToTransactionSystemEvent(
                    $event->getTransactionId(),
                    new CustomerId($customerId),
                    $transaction->getGrossValue(),
                    $transaction->getGrossValueWithoutDeliveryCosts(),
                    $transaction->getAmountExcludedForLevel(),
                    $transactionsCount
                )]
            );

            if ($transactionsCount == 0) {
                $this->eventDispatcher->dispatch(
                    TransactionSystemEvents::CUSTOMER_FIRST_TRANSACTION,
                    [new CustomerFirstTransactionSystemEvent(
                        $event->getTransactionId(),
                        new CustomerId($customerId)
                    )]
                );
            }
            $this->eventDispatcher->dispatch(
                CustomerSystemEvents::CUSTOMER_UPDATED,
                [new CustomerUpdatedSystemEvent(new ClientId($customerId))]
            );
        }
    }

    /**
     * @param DomainMessage $domainMessage
     */
    public function handle(DomainMessage $domainMessage)
    {
        $event = $domainMessage->getPayload();

        if ($event instanceof TransactionWasRegistered) {
            $this->onTransactionRegistered($event);
        }
    }
}
