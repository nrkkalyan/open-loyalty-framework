<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Domain\Exception;

/**
 * Class EmailAlreadyExistsException.
 */
class EmailAlreadyExistsException extends SellerValidationException
{
    protected $message = 'seller with such email already exists';
}
