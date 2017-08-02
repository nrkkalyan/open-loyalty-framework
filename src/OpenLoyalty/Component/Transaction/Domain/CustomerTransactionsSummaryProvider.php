<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Transaction\Domain;

interface CustomerTransactionsSummaryProvider
{
    /**
     * @param CustomerId $customerId
     *
     * @return int
     */
    public function getTransactionsCount(CustomerId $customerId);
}
