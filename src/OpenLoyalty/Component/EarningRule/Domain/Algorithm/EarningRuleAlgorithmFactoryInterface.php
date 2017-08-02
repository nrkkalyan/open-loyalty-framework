<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\EarningRule\Domain\Algorithm;

/**
 * Interface EarningRuleAlgorithmFactoryInterface.
 */
interface EarningRuleAlgorithmFactoryInterface
{
    /**
     * @param $class
     *
     * @return EarningRuleAlgorithmInterface
     */
    public function getAlgorithm($class);
}
