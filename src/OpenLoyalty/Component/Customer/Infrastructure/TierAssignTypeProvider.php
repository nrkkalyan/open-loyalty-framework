<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Infrastructure;

interface TierAssignTypeProvider
{
    const TYPE_POINTS = 'points';
    const TYPE_TRANSACTIONS = 'transactions';

    /**
     * @return string
     */
    public function getType();
}
