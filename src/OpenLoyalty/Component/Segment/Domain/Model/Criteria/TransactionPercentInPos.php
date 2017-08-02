<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain\Model\Criteria;

use OpenLoyalty\Component\Segment\Domain\CriterionId;
use OpenLoyalty\Component\Segment\Domain\Model\Criterion;
use OpenLoyalty\Component\Segment\Domain\PosId;
use Assert\Assertion as Assert;

/**
 * Class TransactionPercentInPos.
 */
class TransactionPercentInPos extends Criterion
{
    /**
     * @var string
     */
    protected $posId;

    /**
     * @var float
     */
    protected $percent;

    /**
     * @return PosId
     */
    public function getPosId()
    {
        return new PosId($this->posId);
    }

    /**
     * @param PosId $posId
     */
    public function setPosId(PosId $posId)
    {
        $this->posId = $posId->__toString();
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

    public static function fromArray(array $data)
    {
        $criterion = new self(new CriterionId($data['criterionId']));
        $criterion->setPosId(new PosId($data['posId']));
        $criterion->setPercent($data['percent']);

        return $criterion;
    }

    public static function validate(array $data)
    {
        parent::validate($data);
        Assert::keyIsset($data, 'posId');
        Assert::keyIsset($data, 'percent');
        Assert::notBlank($data, 'posId');
        Assert::string($data['posId']);
        Assert::float($data['percent']);
        Assert::range($data['percent'], 0, 1);
    }
}
