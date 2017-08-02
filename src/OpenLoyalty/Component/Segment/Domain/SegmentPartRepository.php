<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain;

use OpenLoyalty\Component\Segment\Domain\Model\SegmentPart;

interface SegmentPartRepository
{
    public function byId(SegmentPartId $segmentPartId);

    public function findAll();

    public function save(SegmentPart $segmentPart);

    public function remove(SegmentPart $segmentPart);
}
