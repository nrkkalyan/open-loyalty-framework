<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain\SystemEvent;

use OpenLoyalty\Component\Segment\Domain\CustomerId;
use OpenLoyalty\Component\Segment\Domain\SegmentId;

/**
 * Class CustomerAddedToSegmentSystemEvent.
 */
class CustomerAddedToSegmentSystemEvent extends SegmentSystemEvent
{
    /**
     * @var CustomerId
     */
    protected $customerId;

    public function __construct(SegmentId $segmentId, CustomerId $customerId)
    {
        parent::__construct($segmentId);
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
