<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Transaction\Domain\SystemEvent;

use OpenLoyalty\Component\Transaction\Domain\CustomerId;
use OpenLoyalty\Component\Transaction\Domain\TransactionId;

/**
 * Class CustomerFirstTransactionSystemEvent.
 */
class CustomerFirstTransactionSystemEvent extends TransactionSystemEvent
{
    /**
     * @var CustomerId
     */
    protected $customerId;

    public function __construct(TransactionId $transactionId, CustomerId $customerId)
    {
        parent::__construct($transactionId, []);
        $this->customerId = $customerId;
    }

    /**
     * @return CustomerId
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }
}
