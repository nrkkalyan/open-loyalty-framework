<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\TransactionBundle\Service;

use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetailsRepository;
use OpenLoyalty\Component\Transaction\Domain\CustomerId;
use OpenLoyalty\Component\Transaction\Domain\CustomerTransactionsSummaryProvider;

/**
 * Class OloyCustomerTransactionsSummaryProvider.
 */
class OloyCustomerTransactionsSummaryProvider implements CustomerTransactionsSummaryProvider
{
    /**
     * @var CustomerDetailsRepository
     */
    protected $customerDetailsRepository;

    /**
     * OloyCustomerTransactionsSummaryProvider constructor.
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
     * @return int
     */
    public function getTransactionsCount(CustomerId $customerId)
    {
        $details = $this->customerDetailsRepository->find($customerId->__toString());
        if (!$details instanceof CustomerDetails) {
            return 0;
        }

        return $details->getTransactionsCount();
    }
}
