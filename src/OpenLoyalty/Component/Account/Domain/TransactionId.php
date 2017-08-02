<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class TransactionId.
 */
class TransactionId implements Identifier
{
    /**
     * @var string
     */
    protected $transactionId;

    /**
     * TransactionId constructor.
     *
     * @param string $transactionId
     */
    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
        Assert::string($transactionId);
        Assert::uuid($transactionId);
    }

    public function __toString()
    {
        return $this->transactionId;
    }
}
