<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain\Model;

use OpenLoyalty\Component\Segment\Domain\CriterionId;
use Assert\Assertion as Assert;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\Anniversary;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\AverageTransactionAmount;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\BoughtInPos;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\BoughtLabels;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\BoughtMakers;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\BoughtSKUs;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\LastPurchaseNDaysBefore;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\PurchaseInPeriod;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\TransactionAmount;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\TransactionCount;
use OpenLoyalty\Component\Segment\Domain\Model\Criteria\TransactionPercentInPos;

/**
 * Class Criterion.
 */
abstract class Criterion
{
    const TYPE_BOUGHT_IN_POS = 'bought_in_pos';
    const TYPE_TRANSACTION_COUNT = 'transaction_count';
    const TYPE_AVERAGE_TRANSACTION_AMOUNT = 'average_transaction_amount';
    const TYPE_ANNIVERSARY = 'anniversary';
    const TYPE_LAST_PURCHASE_N_DAYS_BEFORE = 'last_purchase_n_days_before';
    const TYPE_TRANSACTION_AMOUNT = 'transaction_amount';
    const TYPE_PURCHASE_PERIOD = 'purchase_period';
    const TYPE_TRANSACTION_PERCENT_IN_POS = 'transaction_percent_in_pos';
    const TYPE_BOUGHT_SKUS = 'bought_skus';
    const TYPE_BOUGHT_MAKERS = 'bought_makers';
    const TYPE_BOUGHT_LABELS = 'bought_labels';

    const TYPE_MAP = [
        self::TYPE_BOUGHT_IN_POS => BoughtInPos::class,
        self::TYPE_TRANSACTION_COUNT => TransactionCount::class,
        self::TYPE_AVERAGE_TRANSACTION_AMOUNT => AverageTransactionAmount::class,
        self::TYPE_ANNIVERSARY => Anniversary::class,
        self::TYPE_LAST_PURCHASE_N_DAYS_BEFORE => LastPurchaseNDaysBefore::class,
        self::TYPE_TRANSACTION_AMOUNT => TransactionAmount::class,
        self::TYPE_PURCHASE_PERIOD => PurchaseInPeriod::class,
        self::TYPE_TRANSACTION_PERCENT_IN_POS => TransactionPercentInPos::class,
        self::TYPE_BOUGHT_SKUS => BoughtSKUs::class,
        self::TYPE_BOUGHT_MAKERS => BoughtMakers::class,
        self::TYPE_BOUGHT_LABELS => BoughtLabels::class,
    ];

    /**
     * @var CriterionId
     */
    protected $criterionId;

    /**
     * @var SegmentPart
     */
    protected $segmentPart;

    /**
     * Criterion constructor.
     *
     * @param CriterionId $criterionId
     */
    public function __construct(CriterionId $criterionId)
    {
        $this->criterionId = $criterionId;
    }

    /**
     * @return CriterionId
     */
    public function getCriterionId()
    {
        return $this->criterionId;
    }

    /**
     * @return SegmentPart
     */
    public function getSegmentPart()
    {
        return $this->segmentPart;
    }

    /**
     * @param SegmentPart $segmentPart
     */
    public function setSegmentPart($segmentPart)
    {
        $this->segmentPart = $segmentPart;
    }

    public static function fromArray(array $data)
    {
        return;
    }

    public static function validate(array $data)
    {
        Assert::keyIsset($data, 'criterionId');
        Assert::notBlank($data, 'criterionId');
    }
}
