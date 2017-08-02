<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Domain;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasActivated;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasDeactivated;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasDeleted;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasRegistered;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasUpdated;

/**
 * Class Seller.
 */
class Seller extends EventSourcedAggregateRoot
{
    /**
     * @var SellerId
     */
    protected $id;

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->id;
    }

    /**
     * @return SellerId
     */
    public function getId()
    {
        return $this->id;
    }

    public static function registerSeller(SellerId $sellerId, array $sellerData)
    {
        $seller = new self();
        $seller->register($sellerId, $sellerData);

        return $seller;
    }

    public function update(array $sellerData)
    {
        $this->apply(
            new SellerWasUpdated($this->getId(), $sellerData)
        );
    }

    public function activate()
    {
        $this->apply(
            new SellerWasActivated($this->getId())
        );
    }

    public function deactivate()
    {
        $this->apply(
            new SellerWasDeactivated($this->getId())
        );
    }

    public function delete()
    {
        $this->apply(
            new SellerWasDeleted($this->getId())
        );
    }

    private function register(SellerId $sellerId, array $sellerData)
    {
        $this->apply(
            new SellerWasRegistered($sellerId, $sellerData)
        );
    }

    public function applySellerWasRegistered(SellerWasRegistered $event)
    {
        $this->id = $event->getSellerId();
    }
}
