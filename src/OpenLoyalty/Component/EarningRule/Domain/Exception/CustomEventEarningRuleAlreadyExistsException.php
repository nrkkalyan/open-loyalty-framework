<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\EarningRule\Domain\Exception;

/**
 * Class CustomEventEarningRuleAlreadyExistsException.
 */
class CustomEventEarningRuleAlreadyExistsException extends EarningRuleException
{
    protected $message = 'Earning rule with such event name already exist';
}
