<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class UpdateCustomerDetails.
 */
class UpdateCustomerDetails extends CustomerCommand
{
    /**
     * @var array
     */
    protected $customerData;

    /**
     * UpdateCustomer constructor.
     *
     * @param CustomerId $customerId
     * @param array      $customerData
     */
    public function __construct(CustomerId $customerId, array $customerData)
    {
        parent::__construct($customerId);
        $this->customerData = $customerData;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }
}
