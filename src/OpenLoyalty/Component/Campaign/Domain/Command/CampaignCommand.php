<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Campaign\Domain\Command;

use OpenLoyalty\Component\Campaign\Domain\CampaignId;

/**
 * Class CampaignCommand.
 */
abstract class CampaignCommand
{
    /**
     * @var CampaignId
     */
    protected $campaignId;

    /**
     * CampaignCommand constructor.
     *
     * @param CampaignId $campaignId
     */
    public function __construct(CampaignId $campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * @return CampaignId
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }
}
