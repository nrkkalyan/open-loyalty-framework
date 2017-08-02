<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\SystemEvent;

use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class CustomerAgreementsUpdatedSystemEvent.
 */
class CustomerAgreementsUpdatedSystemEvent extends CustomerSystemEvent
{
    /**
     * @var array
     */
    protected $changeSet;

    public function __construct(CustomerId $customerId, array $changeSet)
    {
        parent::__construct($customerId);
        $this->changeSet = $changeSet;
    }

    /**
     * @return array
     */
    public function getChangeSet()
    {
        return $this->changeSet;
    }
}
