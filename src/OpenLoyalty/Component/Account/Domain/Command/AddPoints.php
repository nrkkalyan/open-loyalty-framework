<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain\Command;

use OpenLoyalty\Component\Account\Domain\AccountId;
use OpenLoyalty\Component\Account\Domain\Model\AddPointsTransfer;

/**
 * Class AddPoints.
 */
class AddPoints extends AccountCommand
{
    /**
     * @var AddPointsTransfer
     */
    protected $pointsTransfer;

    public function __construct(AccountId $accountId, AddPointsTransfer $pointsTransfer)
    {
        parent::__construct($accountId);
        $this->pointsTransfer = $pointsTransfer;
    }

    /**
     * @return AddPointsTransfer
     */
    public function getPointsTransfer()
    {
        return $this->pointsTransfer;
    }
}
