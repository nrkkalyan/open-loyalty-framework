<?php

namespace OpenLoyalty\Component\Account\Tests\Infrastructure\SytemEvent\Listener;

use OpenLoyalty\Component\Account\Domain\AccountId;
use OpenLoyalty\Component\Account\Domain\Command\AddPoints;
use OpenLoyalty\Component\Account\Domain\Model\AddPointsTransfer;
use OpenLoyalty\Component\Account\Domain\PointsTransferId;
use OpenLoyalty\Component\Account\Domain\SystemEvent\AccountCreatedSystemEvent;
use OpenLoyalty\Component\Account\Domain\SystemEvent\CustomEventOccurredSystemEvent;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerLoggedInSystemEvent;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\NewsletterSubscriptionSystemEvent;
use OpenLoyalty\Component\Transaction\Domain\CustomerId;
use OpenLoyalty\Component\Transaction\Domain\SystemEvent\CustomerFirstTransactionSystemEvent;
use OpenLoyalty\Component\Transaction\Domain\TransactionId;
use OpenLoyalty\Component\Account\Infrastructure\SystemEvent\Listener\ApplyEarningRuleToEventListener;


/**
 * Class ApplyEarningRuleToEventListenerTest.
 */
class ApplyEarningRuleToEventListenerTest extends BaseApplyEarningRuleListenerTest
{
    /**
     * @test
     */
    public function it_adds_points_on_registration()
    {
        $accountId = new AccountId($this->uuid);
        $expected = new AddPoints($accountId, new AddPointsTransfer(
            new PointsTransferId($this->uuid),
            10,
            null,
            false
        ));

        $listener = new ApplyEarningRuleToEventListener(
            $this->getCommandBus($expected),
            $this->getAccountDetailsRepository(),
            $this->getUuidGenerator(),
            $this->getApplierForEvent(10)
        );

        $listener->onCustomerRegistered(new AccountCreatedSystemEvent($accountId));
    }

    /**
     * @test
     */
    public function it_adds_points_on_first_transaction()
    {
        $accountId = new AccountId($this->uuid);
        $expected = new AddPoints($accountId, new AddPointsTransfer(
            new PointsTransferId($this->uuid),
            10,
            null,
            false
        ));

        $listener = new ApplyEarningRuleToEventListener(
            $this->getCommandBus($expected),
            $this->getAccountDetailsRepository(),
            $this->getUuidGenerator(),
            $this->getApplierForEvent(10)
        );

        $listener->onFirstTransaction(new CustomerFirstTransactionSystemEvent(new TransactionId($this->uuid), new CustomerId($this->uuid)));
    }

    /**
     * @test
     */
    public function it_adds_points_on_login()
    {
        $accountId = new AccountId($this->uuid);
        $expected = new AddPoints($accountId, new AddPointsTransfer(
            new PointsTransferId($this->uuid),
            10,
            null,
            false
        ));

        $listener = new ApplyEarningRuleToEventListener(
            $this->getCommandBus($expected),
            $this->getAccountDetailsRepository(),
            $this->getUuidGenerator(),
            $this->getApplierForEvent(10)
        );

        $listener->onCustomerLogin(new CustomerLoggedInSystemEvent(new \OpenLoyalty\Component\Customer\Domain\CustomerId($this->uuid)));
    }

    /**
     * @test
     */
    public function it_adds_points_on_newsletter_subscription()
    {
        $accountId = new AccountId($this->uuid);
        $expected = new AddPoints($accountId, new AddPointsTransfer(
            new PointsTransferId($this->uuid),
            100,
            null,
            false,
            null,
            "Newsletter subscription"
        ));

        $listener = new ApplyEarningRuleToEventListener(
            $this->getCommandBus($expected),
            $this->getAccountDetailsRepository(),
            $this->getUuidGenerator(),
            $this->getApplierForEvent(100)
        );

        $customerId = new \OpenLoyalty\Component\Customer\Domain\CustomerId($this->uuid);
        $listener->onNewsletterSubscription(new NewsletterSubscriptionSystemEvent($customerId));
    }

    /**
     * @test
     */
    public function it_adds_points_on_custom_event()
    {
        $accountId = new AccountId($this->uuid);
        $expected = new AddPoints($accountId, new AddPointsTransfer(
            new PointsTransferId($this->uuid),
            100
        ));

        $listener = new ApplyEarningRuleToEventListener(
            $this->getCommandBus($expected),
            $this->getAccountDetailsRepository(),
            $this->getUuidGenerator(),
            $this->getApplierForEvent(100)
        );

        $customerId = new \OpenLoyalty\Component\Account\Domain\CustomerId($this->uuid);
        $listener->onCustomEvent(new CustomEventOccurredSystemEvent($customerId, 'facebook_like'));
    }
}
