<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain;

use Assert\Assertion as Assert;
use OpenLoyalty\Component\Core\Domain\Model\Identifier;

class SegmentId implements Identifier
{
    /**
     * @var string
     */
    protected $segmentId;

    /**
     * SegmentId constructor.
     *
     * @param string $segmentId
     */
    public function __construct($segmentId)
    {
        Assert::string($segmentId);
        Assert::uuid($segmentId);

        $this->segmentId = $segmentId;
    }

    public function __toString()
    {
        return $this->segmentId;
    }
}
