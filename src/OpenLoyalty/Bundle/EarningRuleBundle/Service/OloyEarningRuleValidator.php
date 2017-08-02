<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\EarningRuleBundle\Service;

use OpenLoyalty\Component\Account\Domain\CustomerId;
use OpenLoyalty\Component\EarningRule\Domain\CustomEventEarningRule;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleId;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleLimit;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleRepository;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleUsageRepository;
use OpenLoyalty\Component\EarningRule\Domain\Model\UsageSubject;
use OpenLoyalty\Component\Account\Infrastructure\EarningRuleLimitValidator;
use OpenLoyalty\Component\Account\Infrastructure\Exception\EarningRuleLimitExceededException;

/**
 * Class OloyEarningRuleValidator.
 */
class OloyEarningRuleValidator implements EarningRuleLimitValidator
{
    /**
     * @var EarningRuleUsageRepository
     */
    protected $earningRuleUsageRepository;

    /**
     * @var EarningRuleRepository
     */
    protected $earningRuleRepository;

    /**
     * OloyEarningRuleValidator constructor.
     *
     * @param EarningRuleUsageRepository $earningRuleUsageRepository
     * @param EarningRuleRepository      $earningRuleRepository
     */
    public function __construct(
        EarningRuleUsageRepository $earningRuleUsageRepository,
        EarningRuleRepository $earningRuleRepository
    ) {
        $this->earningRuleUsageRepository = $earningRuleUsageRepository;
        $this->earningRuleRepository = $earningRuleRepository;
    }

    /**
     * @param $earningRuleId
     * @param CustomerId $customerId
     *
     * @throws EarningRuleLimitExceededException
     */
    public function validate($earningRuleId, CustomerId $customerId)
    {
        $repo = $this->earningRuleUsageRepository;
        $earningRuleId = new EarningRuleId($earningRuleId);
        /** @var CustomEventEarningRule $earningRule */
        $earningRule = $this->earningRuleRepository->byId($earningRuleId);
        if (!$earningRule instanceof CustomEventEarningRule) {
            return;
        }
        $limit = $earningRule->getLimit();
        if (!$limit || !$limit->isActive()) {
            return;
        }
        $subject = new UsageSubject($customerId->__toString());

        switch ($limit->getPeriod()) {
            case EarningRuleLimit::PERIOD_DAY:
                $usage = $repo->countDailyUsage($earningRuleId, $subject);
                break;
            case EarningRuleLimit::PERIOD_WEEK:
                $usage = $repo->countWeeklyUsage($earningRuleId, $subject);
                break;
            case EarningRuleLimit::PERIOD_MONTH:
                $usage = $repo->countMonthlyUsage($earningRuleId, $subject);
                break;
            default:
                $usage = 0;
        }
        if ($usage >= $limit->getLimit()) {
            throw new EarningRuleLimitExceededException();
        }
    }
}
