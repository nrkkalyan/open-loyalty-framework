<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\SystemEvent;

use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\LevelId;

/**
 * Class CustomerLevelChangedSystemEvent.
 */
class CustomerLevelChangedSystemEvent extends CustomerSystemEvent
{
    /**
     * @var LevelId
     */
    protected $levelId;

    public function __construct(CustomerId $customerId, LevelId $levelId)
    {
        parent::__construct($customerId);
        $this->levelId = $levelId;
    }

    /**
     * @return LevelId
     */
    public function getLevelId()
    {
        return $this->levelId;
    }
}
