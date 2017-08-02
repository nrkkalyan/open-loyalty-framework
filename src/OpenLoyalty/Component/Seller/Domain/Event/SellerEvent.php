<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Domain\Event;

use Broadway\Serializer\SerializableInterface;
use OpenLoyalty\Component\Seller\Domain\SellerId;

/**
 * Class SellerEvent.
 */
abstract class SellerEvent implements SerializableInterface
{
    /**
     * @var SellerId
     */
    protected $sellerId;

    /**
     * SellerEvent constructor.
     *
     * @param SellerId $sellerId
     */
    public function __construct(SellerId $sellerId)
    {
        $this->sellerId = $sellerId;
    }

    /**
     * @return SellerId
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    public function serialize()
    {
        return [
            'sellerId' => $this->sellerId->__toString(),
        ];
    }
}
