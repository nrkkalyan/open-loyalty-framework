<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain\Exception;

/**
 * Class CannotBeCanceled.
 */
class CannotBeCanceledException extends \Exception
{
    protected $message = 'this transfer cannot be canceled';
}
