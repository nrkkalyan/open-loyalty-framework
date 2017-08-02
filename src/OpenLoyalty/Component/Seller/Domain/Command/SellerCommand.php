<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Domain\Command;

use OpenLoyalty\Component\Seller\Domain\SellerId;

/**
 * Class SellerCommand.
 */
abstract class SellerCommand
{
    /**
     * @var SellerId
     */
    protected $sellerId;

    /**
     * SellerCommand constructor.
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
}
