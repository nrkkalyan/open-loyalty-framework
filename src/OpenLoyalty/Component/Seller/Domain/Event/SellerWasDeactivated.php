<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Domain\Event;

use OpenLoyalty\Component\Seller\Domain\SellerId;

/**
 * Class SellerWasDeactivated.
 */
class SellerWasDeactivated extends SellerEvent
{
    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self(new SellerId($data['sellerId']));
    }
}
