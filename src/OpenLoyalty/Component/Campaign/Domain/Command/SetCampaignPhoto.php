<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Campaign\Domain\Command;

use OpenLoyalty\Component\Campaign\Domain\CampaignId;
use OpenLoyalty\Component\Campaign\Domain\Model\CampaignPhoto;

/**
 * Class SetCampaignPhoto.
 */
class SetCampaignPhoto extends CampaignCommand
{
    /**
     * @var CampaignPhoto
     */
    protected $campaignPhoto;

    public function __construct(CampaignId $campaignId, CampaignPhoto $campaignPhoto = null)
    {
        parent::__construct($campaignId);
        $this->campaignPhoto = $campaignPhoto;
    }

    /**
     * @return CampaignPhoto
     */
    public function getCampaignPhoto()
    {
        return $this->campaignPhoto;
    }
}
