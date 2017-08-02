<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class UpdateCustomerAddress.
 */
class UpdateCustomerAddress extends CustomerCommand
{
    protected $addressData;

    /**
     * UpdateCustomerAddress constructor.
     *
     * @param CustomerId $customerId
     * @param $addressData
     */
    public function __construct(CustomerId $customerId, $addressData)
    {
        parent::__construct($customerId);
        $this->addressData = $addressData;
    }

    /**
     * @return mixed
     */
    public function getAddressData()
    {
        return $this->addressData;
    }
}
