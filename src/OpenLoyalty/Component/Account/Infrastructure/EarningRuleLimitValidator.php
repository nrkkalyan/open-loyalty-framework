<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Infrastructure;

use OpenLoyalty\Component\Account\Domain\CustomerId;
use OpenLoyalty\Component\Account\Infrastructure\Exception\EarningRuleLimitExceededException;

interface EarningRuleLimitValidator
{
    /**
     * @param $earningRuleId
     * @param CustomerId $customerId
     *
     * @throws EarningRuleLimitExceededException
     */
    public function validate($earningRuleId, CustomerId $customerId);
}
