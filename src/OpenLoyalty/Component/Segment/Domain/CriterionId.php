<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class CriterionId.
 */
class CriterionId implements Identifier
{
    /**
     * @var string
     */
    protected $criterionId;

    /**
     * CriterionId constructor.
     *
     * @param string $criterionId
     */
    public function __construct($criterionId)
    {
        Assert::string($criterionId);
        Assert::uuid($criterionId);

        $this->criterionId = $criterionId;
    }

    public function __toString()
    {
        return $this->criterionId;
    }
}
