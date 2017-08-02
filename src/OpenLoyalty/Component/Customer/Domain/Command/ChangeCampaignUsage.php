<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CampaignId;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\Model\Coupon;

/**
 * Class ChangeCampaignUsage.
 */
class ChangeCampaignUsage extends CustomerCommand
{
    /**
     * @var CampaignId
     */
    protected $campaignId;

    /**
     * @var bool
     */
    protected $used;

    /**
     * @var Coupon
     */
    protected $coupon;

    public function __construct(CustomerId $customerId, CampaignId $campaignId, Coupon $coupon, $used)
    {
        parent::__construct($customerId);
        $this->campaignId = $campaignId;
        $this->used = $used;
        $this->coupon = $coupon;
    }

    /**
     * @return CampaignId
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * @return Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }
}
