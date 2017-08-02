<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain\Segmentation\CriteriaEvaluators;

use OpenLoyalty\Component\Transaction\Domain\CustomerId;

interface CustomerValidator
{
    /**
     * @param CustomerId $customerId
     *
     * @return bool
     */
    public function isValid(CustomerId $customerId);
}
