<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain\Segmentation\CriteriaEvaluators;

use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetailsRepository;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\TransactionCount;
use OpenLoyalty\Component\Segment\Domain\Model\Criterion;

/**
 * Class TransactionCountEvaluator.
 */
class TransactionCountEvaluator implements Evaluator
{
    /**
     * @var CustomerDetailsRepository
     */
    protected $customerDetailsRepository;

    /**
     * TransactionCountEvaluator constructor.
     *
     * @param CustomerDetailsRepository $customerDetailsRepository
     */
    public function __construct(CustomerDetailsRepository $customerDetailsRepository)
    {
        $this->customerDetailsRepository = $customerDetailsRepository;
    }

    /**
     * @param Criterion $criterion
     *
     * @return array
     */
    public function evaluate(Criterion $criterion)
    {
        if (!$criterion instanceof TransactionCount) {
            return [];
        }

        $customers = $this->customerDetailsRepository->findAllWithTransactionCountBetween(
            $criterion->getMin(),
            $criterion->getMax()
        );

        $result = [];

        /** @var CustomerDetails $customer */
        foreach ($customers as $customer) {
            $result[$customer->getCustomerId()->__toString()] = $customer->getCustomerId()->__toString();
        }

        return $result;
    }

    /**
     * @param Criterion $criterion
     *
     * @return bool
     */
    public function support(Criterion $criterion)
    {
        return $criterion instanceof TransactionCount;
    }
}
