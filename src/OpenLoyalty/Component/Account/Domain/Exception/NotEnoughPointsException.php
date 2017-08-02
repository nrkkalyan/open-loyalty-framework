<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain\Exception;

/**
 * Class NotEnoughPointsException.
 */
class NotEnoughPointsException extends \InvalidArgumentException
{
    protected $message = 'Not enough points';
}
