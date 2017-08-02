<?php

namespace OpenLoyalty\Component\Account\Infrastructure\SystemEvent\Listener;

use Broadway\CommandHandling\CommandBusInterface;
use Broadway\ReadModel\RepositoryInterface;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use OpenLoyalty\Component\Account\Domain\Command\AddPoints;
use OpenLoyalty\Component\Account\Domain\Model\AddPointsTransfer;
use OpenLoyalty\Component\Account\Domain\PointsTransferId;
use OpenLoyalty\Component\Account\Domain\ReadModel\AccountDetails;
use OpenLoyalty\Component\Customer\Domain\Command\InvitedCustomerMadePurchase;
use OpenLoyalty\Component\EarningRule\Domain\ReferralEarningRule;
use OpenLoyalty\Component\Account\Infrastructure\EarningRuleApplier;
use OpenLoyalty\Component\Account\Infrastructure\Model\ReferralEvaluationResult;

/**
 * Class BaseApplyEarningRuleToEventListener.
 */
abstract class BaseApplyEarningRuleListener
{
    /**
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * @var RepositoryInterface
     */
    protected $accountDetailsRepository;

    /**
     * @var UuidGeneratorInterface
     */
    protected $uuidGenerator;

    /**
     * @var EarningRuleApplier
     */
    protected $earningRuleApplier;

    /**
     * ApplyEarningRuleToTransactionListener constructor.
     *
     * @param CommandBusInterface    $commandBus
     * @param RepositoryInterface    $accountDetailsRepository
     * @param UuidGeneratorInterface $uuidGenerator
     * @param EarningRuleApplier     $earningRuleApplier
     */
    public function __construct(
        CommandBusInterface $commandBus,
        RepositoryInterface $accountDetailsRepository,
        UuidGeneratorInterface $uuidGenerator,
        EarningRuleApplier $earningRuleApplier
    ) {
        $this->commandBus = $commandBus;
        $this->accountDetailsRepository = $accountDetailsRepository;
        $this->uuidGenerator = $uuidGenerator;
        $this->earningRuleApplier = $earningRuleApplier;
    }

    /**
     * @param string $customerId
     *
     * @return null|AccountDetails
     */
    protected function getAccountDetails($customerId)
    {
        $accounts = $this->accountDetailsRepository->findBy(['customerId' => $customerId]);
        if (count($accounts) == 0) {
            return;
        }
        /** @var AccountDetails $account */
        $account = reset($accounts);

        if (!$account instanceof AccountDetails) {
            return;
        }

        return $account;
    }

    protected function evaluateReferral($eventName, $customerId)
    {
        $results = $this->earningRuleApplier->evaluateReferralEvent($eventName, $customerId);

        /** @var ReferralEvaluationResult $result */
        foreach ($results as $result) {
            $rewardedCustomers = [];
            if ($result->getRewardType() == ReferralEarningRule::TYPE_BOTH) {
                $rewardedCustomers[] = [
                    'id' => $result->getInvitation()->getRecipientId(),
                    'comment' => sprintf('%s customer referral', (string) $result->getInvitation()->getReferrerId()),
                ];
                $rewardedCustomers[] = [
                    'id' => $result->getInvitation()->getReferrerId(),
                    'comment' => sprintf('Referring customer %s', (string) $result->getInvitation()->getRecipientId()),
                ];
            } elseif ($result->getRewardType() == ReferralEarningRule::TYPE_REFERRER) {
                $rewardedCustomers[] = [
                    'id' => $result->getInvitation()->getReferrerId(),
                    'comment' => sprintf('Referring customer %s', (string) $result->getInvitation()->getRecipientId()),
                ];
            } elseif ($result->getRewardType() == ReferralEarningRule::TYPE_REFERRED) {
                $rewardedCustomers[] = [
                    'id' => $result->getInvitation()->getRecipientId(),
                    'comment' => sprintf('%s customer referral', (string) $result->getInvitation()->getReferrerId()),
                ];
            }

            foreach ($rewardedCustomers as $customer) {
                $account = $this->getAccountDetails((string) $customer['id']);
                if (!$account) {
                    continue;
                }
                $this->commandBus->dispatch(
                    new AddPoints($account->getAccountId(), new AddPointsTransfer(
                        new PointsTransferId($this->uuidGenerator->generate()),
                        $result->getPoints(),
                        null,
                        false,
                        null,
                        $customer['comment']
                    ))
                );
                if ($eventName == ReferralEarningRule::EVENT_EVERY_PURCHASE || $eventName == ReferralEarningRule::EVENT_FIRST_PURCHASE) {
                    $this->commandBus->dispatch(
                        new InvitedCustomerMadePurchase($result->getInvitation()->getInvitationId())
                    );
                }
            }
        }
    }
}
