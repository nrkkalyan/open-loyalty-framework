<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class UpdateCustomerLoyaltyCardNumber.
 */
class UpdateCustomerLoyaltyCardNumber extends CustomerCommand
{
    /**
     * @var string
     */
    protected $cardNumber;

    /**
     * UpdateCustomerLoyaltyCardNumber constructor.
     *
     * @param CustomerId $customerId
     * @param $cardNumber
     */
    public function __construct(CustomerId $customerId, $cardNumber)
    {
        parent::__construct($customerId);
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }
}
