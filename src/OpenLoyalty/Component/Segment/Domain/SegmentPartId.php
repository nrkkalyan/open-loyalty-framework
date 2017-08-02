<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain;

use Assert\Assertion as Assert;
use OpenLoyalty\Component\Core\Domain\Model\Identifier;

/**
 * Class SegmentPartId.
 */
class SegmentPartId implements Identifier
{
    /**
     * @var string
     */
    protected $segmentPartId;

    /**
     * SegmentPartId constructor.
     *
     * @param string $segmentPartId
     */
    public function __construct($segmentPartId)
    {
        Assert::string($segmentPartId);
        Assert::uuid($segmentPartId);

        $this->segmentPartId = $segmentPartId;
    }

    public function __toString()
    {
        return $this->segmentPartId;
    }
}
