<?php

namespace OpenLoyalty\Component\Account\Tests\Infrastructure\Event\Listener;

use Broadway\CommandHandling\CommandBusInterface;
use Broadway\ReadModel\RepositoryInterface;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use OpenLoyalty\Component\Account\Domain\AccountId;
use OpenLoyalty\Component\Account\Domain\Command\SpendPoints;
use OpenLoyalty\Component\Account\Domain\Model\SpendPointsTransfer;
use OpenLoyalty\Component\Account\Domain\PointsTransferId;
use OpenLoyalty\Component\Account\Domain\ReadModel\AccountDetails;
use OpenLoyalty\Component\Account\Infrastructure\Event\Listener\SpendPointsOnCampaignListener;
use OpenLoyalty\Component\Customer\Domain\CampaignId;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\Event\CampaignWasBoughtByCustomer;
use OpenLoyalty\Component\Customer\Domain\Model\Coupon;

/**
 * Class SpendPointsOnCampaignListenerTest.
 */
class SpendPointsOnCampaignListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $uuid = '00000000-0000-0000-0000-000000000000';

    protected function getUuidGenerator()
    {
        $mock = $this->getMockBuilder(UuidGeneratorInterface::class)->getMock();
        $mock->method('generate')->willReturn($this->uuid);

        return $mock;
    }

    /**
     * @test
     */
    public function it_spend_points_when_customer_bought_campaign()
    {
        $listener = new SpendPointsOnCampaignListener(
            $this->getCommandBus(
                new SpendPoints(
                    new AccountId($this->uuid),
                    new SpendPointsTransfer(
                        new PointsTransferId($this->uuid),
                        10,
                        null,
                        false,
                        'test, coupon: 123'
                    )
                )
            ),
            $this->getAccountDetailsRepository(),
            $this->getUuidGenerator()
        );
        $listener->onCustomerBoughtCampaign(new CampaignWasBoughtByCustomer(
            new CustomerId($this->uuid),
            new CampaignId($this->uuid),
            'test',
            10,
            new Coupon('123')
        ));
    }

    protected function getAccountDetailsRepository()
    {
        $account = $this->getMockBuilder(AccountDetails::class)->disableOriginalConstructor()->getMock();
        $account->method('getAccountId')->willReturn(new AccountId($this->uuid));

        $repo = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $repo->method('findBy')->with($this->arrayHasKey('customerId'))->willReturn([$account]);

        return $repo;
    }

    protected function getCommandBus($expected)
    {
        $mock = $this->getMockBuilder(CommandBusInterface::class)->getMock();
        $mock->method('dispatch')->with($this->equalTo($expected));

        return $mock;
    }
}
