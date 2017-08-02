<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Transaction\Domain;

use Assert\Assertion as Assert;
use OpenLoyalty\Component\Core\Domain\Model\Identifier;

/**
 * Class TransactionId.
 */
class TransactionId implements Identifier
{
    private $transactionId;

    /**
     * @param string $transactionId
     */
    public function __construct($transactionId)
    {
        Assert::string($transactionId);
        Assert::uuid($transactionId);

        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->transactionId;
    }
}
