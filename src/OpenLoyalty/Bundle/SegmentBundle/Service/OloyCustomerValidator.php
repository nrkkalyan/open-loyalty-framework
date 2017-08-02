<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\SegmentBundle\Service;

use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetailsRepository;
use OpenLoyalty\Component\Segment\Domain\Segmentation\CriteriaEvaluators\CustomerValidator;
use OpenLoyalty\Component\Transaction\Domain\CustomerId;

/**
 * Class OloyCustomerValidator.
 */
class OloyCustomerValidator implements CustomerValidator
{
    /**
     * @var CustomerDetailsRepository
     */
    protected $customerDetailsRepository;

    /**
     * OloyCustomerValidator constructor.
     *
     * @param CustomerDetailsRepository $customerDetailsRepository
     */
    public function __construct(CustomerDetailsRepository $customerDetailsRepository)
    {
        $this->customerDetailsRepository = $customerDetailsRepository;
    }

    /**
     * @param CustomerId $customerId
     *
     * @return bool
     */
    public function isValid(CustomerId $customerId)
    {
        $customerDetails = $this->customerDetailsRepository->find($customerId->__toString());
        if (!$customerDetails instanceof CustomerDetails) {
            return false;
        }

        return $customerDetails->isActive();
    }
}
