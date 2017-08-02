<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain\Command;

use OpenLoyalty\Component\Account\Domain\AccountId;

/**
 * Class AccountCommand.
 */
abstract class AccountCommand
{
    /**
     * @var AccountId
     */
    protected $accountId;

    /**
     * AccountCommand constructor.
     *
     * @param AccountId $accountId
     */
    public function __construct(AccountId $accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * @return AccountId
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}
