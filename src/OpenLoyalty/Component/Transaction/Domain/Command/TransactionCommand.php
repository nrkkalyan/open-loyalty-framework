<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Transaction\Domain\Command;

use OpenLoyalty\Component\Transaction\Domain\TransactionId;

/**
 * Class TransactionCommand.
 */
abstract class TransactionCommand
{
    /**
     * @var TransactionId
     */
    protected $transactionId;

    /**
     * TransactionCommand constructor.
     *
     * @param TransactionId $transactionId
     */
    public function __construct(TransactionId $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
