<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Campaign\Infrastructure\ReadModel;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListenerInterface;
use OpenLoyalty\Component\Campaign\Domain\CampaignId;
use OpenLoyalty\Component\Campaign\Domain\CustomerId;
use OpenLoyalty\Component\Campaign\Domain\Model\Coupon;
use OpenLoyalty\Component\Campaign\Domain\ReadModel\CouponUsage;
use OpenLoyalty\Component\Campaign\Domain\ReadModel\CouponUsageRepository;
use OpenLoyalty\Component\Customer\Domain\Event\CampaignWasBoughtByCustomer;

/**
 * Class CouponUsageProjector.
 */
class CouponUsageProjector implements EventListenerInterface
{
    /**
     * @var CouponUsageRepository
     */
    protected $repository;

    /**
     * CouponUsageProjector constructor.
     *
     * @param CouponUsageRepository $repository
     */
    public function __construct(CouponUsageRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function handleCampaignWasBoughtByCustomer(CampaignWasBoughtByCustomer $event)
    {
        $this->storeCouponUsage(
            new CampaignId($event->getCampaignId()->__toString()),
            new CustomerId($event->getCustomerId()->__toString()),
            new Coupon($event->getCoupon()->getCode())
        );
    }

    public function storeCouponUsage(CampaignId $campaignId, CustomerId $customerId, Coupon $coupon)
    {
        $readModel = $this->getReadModel($campaignId, $customerId, $coupon);
        $this->repository->save($readModel);
    }

    public function removeAll()
    {
        foreach ($this->repository->findAll() as $segmented) {
            $this->repository->remove($segmented->getId());
        }
    }

    private function getReadModel(CampaignId $campaignId, CustomerId $customerId, Coupon $coupon)
    {
        $readModel = $this->repository->find($campaignId->__toString().'_'.$customerId->__toString().'_'.$coupon->getCode());
        if (null === $readModel) {
            $readModel = new CouponUsage($campaignId, $customerId, $coupon, 1);
        } elseif (null !== $readModel->getUsage()) {
            $usage = $readModel->getUsage() + 1;
            $readModel = new CouponUsage($campaignId, $customerId, $coupon, $usage);
        }

        return $readModel;
    }

    /**
     * @param DomainMessage $domainMessage
     */
    public function handle(DomainMessage $domainMessage)
    {
        $event = $domainMessage->getPayload();
        if ($event instanceof CampaignWasBoughtByCustomer) {
            $this->handleCampaignWasBoughtByCustomer($event);
        }
    }
}
