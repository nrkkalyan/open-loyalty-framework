<?php

namespace OpenLoyalty\Component\Seller\Tests\Domain\Command;

use OpenLoyalty\Component\Seller\Domain\Command\DeactivateSeller;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasDeactivated;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasRegistered;
use OpenLoyalty\Component\Seller\Domain\PosId;
use OpenLoyalty\Component\Seller\Domain\SellerId;

/**
 * Class DeactivateSellerTest.
 */
class DeactivateSellerTest extends SellerCommandHandlerTest
{
    /**
     * @test
     */
    public function it_deactivates_seller()
    {
        $sellerId = new SellerId('00000000-0000-0000-0000-000000000000');
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'open@loyalty.com',
            'phone' => '123456789',
            'posId' => new PosId('00000000-0000-0000-0000-000000000000'),
            'createdAt' => new \DateTime(),
        ];
        $this->scenario
            ->withAggregateId($sellerId)
            ->given([new SellerWasRegistered($sellerId, $data)])
            ->when(new DeactivateSeller($sellerId))
            ->then(array(
                new SellerWasDeactivated($sellerId)
            ));
    }
}
