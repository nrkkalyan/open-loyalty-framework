<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CampaignId;
use OpenLoyalty\Component\Customer\Domain\Command\BuyCampaign;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\Event\CampaignWasBoughtByCustomer;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerWasRegistered;
use OpenLoyalty\Component\Customer\Domain\Model\Coupon;

/**
 * Class BuyCampaignTest.
 */
class BuyCampaignTest extends CustomerCommandHandlerTest
{
    /**
     * @test
     */
    public function it_buys_campaign()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');
        $campaignId = new CampaignId('00000000-0000-0000-0000-000000000001');

        $this->scenario
            ->withAggregateId($customerId)
            ->given([
                new CustomerWasRegistered($customerId, CustomerCommandHandlerTest::getCustomerData()),
            ])
            ->when(new BuyCampaign($customerId, $campaignId, 'test', 99,  new Coupon('123')))
            ->then([
                new CampaignWasBoughtByCustomer($customerId, $campaignId, 'test', 99, new Coupon('123'))
            ]);
    }
}
