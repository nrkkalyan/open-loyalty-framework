<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\EarningRule\Domain\Algorithm;

use OpenLoyalty\Component\EarningRule\Domain\EarningRule;
use OpenLoyalty\Component\EarningRule\Domain\PointsEarningRule;

/**
 * Class PointsEarningRuleAlgorithm.
 */
class PointsEarningRuleAlgorithm extends AbstractRuleAlgorithm
{
    /**
     * PointsEarningRuleAlgorithm constructor.
     */
    public function __construct()
    {
        parent::__construct(1);
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate(RuleEvaluationContextInterface $context, EarningRule $rule)
    {
        if (!$rule instanceof PointsEarningRule) {
            throw new \InvalidArgumentException(get_class($rule));
        }

        $totalValue = $rule->isExcludeDeliveryCost()
            ? $context->getTransaction()->getGrossValueWithoutDeliveryCosts()
            : $context->getTransaction()->getGrossValue();

        // skip transaction bellow min order value
        if (!empty($rule->getMinOrderValue()) && $totalValue < $rule->getMinOrderValue()) {
            return;
        }

        if ($rule->isExcludeDeliveryCost()) {
            $filteredItems = $context->getTransaction()->getFilteredItems(
                $rule->getExcludedSKUs(),
                $rule->getExcludedLabels(),
                true
            );
        } else {
            $filteredItems = $context->getTransaction()->getFilteredItems(
                $rule->getExcludedSKUs(),
                $rule->getExcludedLabels()
            );
        }

        foreach ($filteredItems as $item) {
            $context->addProductPoints(
                $item->getSku()->getCode(),
                $item->getGrossValue() * $rule->getPointValue()
            );
        }
    }
}
