<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Account\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class PointsTransferId.
 */
class PointsTransferId implements Identifier
{
    /**
     * @var string
     */
    protected $pointsTransferId;

    /**
     * @param string $pointsTransferId
     */
    public function __construct($pointsTransferId)
    {
        Assert::string($pointsTransferId);
        Assert::uuid($pointsTransferId);

        $this->pointsTransferId = $pointsTransferId;
    }

    public function __toString()
    {
        return $this->pointsTransferId;
    }
}
