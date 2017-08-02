<?php

namespace OpenLoyalty\Component\Seller\Tests\Domain\Command;

use OpenLoyalty\Component\Seller\Domain\Command\DeleteSeller;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasDeleted;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasRegistered;
use OpenLoyalty\Component\Seller\Domain\PosId;
use OpenLoyalty\Component\Seller\Domain\SellerId;

/**
 * Class DeleteSellerTest.
 */
class DeleteSellerTest extends SellerCommandHandlerTest
{
    /**
     * @test
     */
    public function it_activates_seller()
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
            ->when(new DeleteSeller($sellerId))
            ->then(array(
                new SellerWasDeleted($sellerId)
            ));
    }
}
