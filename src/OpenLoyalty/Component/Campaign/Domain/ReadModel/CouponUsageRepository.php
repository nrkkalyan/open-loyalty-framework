<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Campaign\Domain\ReadModel;

use Broadway\ReadModel\RepositoryInterface;
use OpenLoyalty\Component\Campaign\Domain\CampaignId;
use OpenLoyalty\Component\Campaign\Domain\CustomerId;

interface CouponUsageRepository extends RepositoryInterface
{
    public function countUsageForCampaign(CampaignId $campaignId);

    public function countUsageForCampaignAndCustomer(CampaignId $campaignId, CustomerId $customerId);

    public function findByCampaign(CampaignId $campaignId);
}
