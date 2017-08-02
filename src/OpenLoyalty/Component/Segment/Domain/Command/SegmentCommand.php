<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain\Command;

use OpenLoyalty\Component\Segment\Domain\SegmentId;

/**
 * Class SegmentCommand.
 */
abstract class SegmentCommand
{
    /**
     * @var SegmentId
     */
    protected $segmentId;

    /**
     * SegmentCommand constructor.
     *
     * @param SegmentId $segmentId
     */
    public function __construct(SegmentId $segmentId)
    {
        $this->segmentId = $segmentId;
    }

    /**
     * @return SegmentId
     */
    public function getSegmentId()
    {
        return $this->segmentId;
    }
}
