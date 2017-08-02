<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain;

interface LevelIdProvider
{
    /**
     * @param $conditionValue
     *
     * @return string
     */
    public function findLevelIdByConditionValueWithTheBiggestReward($conditionValue);
}
