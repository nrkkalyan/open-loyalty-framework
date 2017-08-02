<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Pos\Domain\SystemEvent;

use OpenLoyalty\Component\Pos\Domain\PosId;

/**
 * Class PosSystemEvent.
 */
abstract class PosSystemEvent
{
    /**
     * @var PosId
     */
    protected $posId;

    /**
     * PosSystemEvent constructor.
     *
     * @param PosId $posId
     */
    public function __construct(PosId $posId)
    {
        $this->posId = $posId;
    }

    /**
     * @return PosId
     */
    public function getPosId()
    {
        return $this->posId;
    }
}
