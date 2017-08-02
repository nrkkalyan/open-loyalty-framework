<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Infrastructure;

interface ExcludeDeliveryCostsProvider
{
    /**
     * @return bool
     */
    public function areExcluded();
}
