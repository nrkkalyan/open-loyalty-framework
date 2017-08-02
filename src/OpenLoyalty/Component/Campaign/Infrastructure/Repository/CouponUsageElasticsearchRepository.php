<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Campaign\Infrastructure\Repository;

use OpenLoyalty\Component\Campaign\Domain\CampaignId;
use OpenLoyalty\Component\Campaign\Domain\CustomerId;
use OpenLoyalty\Component\Campaign\Domain\ReadModel\CouponUsageRepository;
use OpenLoyalty\Component\Core\Infrastructure\Repository\OloyElasticsearchRepository;

/**
 * Class CouponUsageElasticsearchRepository.
 */
class CouponUsageElasticsearchRepository extends OloyElasticsearchRepository implements CouponUsageRepository
{
    public function countUsageForCampaign(CampaignId $campaignId)
    {
        return $this->countTotal(['campaignId' => $campaignId->__toString()]);
    }

    public function countUsageForCampaignAndCustomer(CampaignId $campaignId, CustomerId $customerId)
    {
        return $this->countTotal([
            'campaignId' => $campaignId->__toString(),
            'customerId' => $customerId->__toString(),
        ]);
    }

    public function findByCampaign(CampaignId $campaignId)
    {
        return $this->findBy(['campaignId' => $campaignId->__toString()]);
    }
}
