<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain\SystemEvent;

use OpenLoyalty\Component\Account\Domain\CustomerId;
use OpenLoyalty\Component\Account\Infrastructure\Model\EvaluationResult;

/**
 * Class CustomEventOccurredSystemEvent.
 */
class CustomEventOccurredSystemEvent
{
    /**
     * @var CustomerId
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var EvaluationResult
     */
    protected $evaluationResult;

    /**
     * CustomEventOccurredSystemEvent constructor.
     *
     * @param CustomerId $customerId
     * @param string     $eventName
     */
    public function __construct(CustomerId $customerId, $eventName)
    {
        $this->customerId = $customerId;
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @return CustomerId
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return EvaluationResult
     */
    public function getEvaluationResult()
    {
        return $this->evaluationResult;
    }

    /**
     * @param EvaluationResult $evaluationResult
     */
    public function setEvaluationResult($evaluationResult)
    {
        $this->evaluationResult = $evaluationResult;
    }
}
