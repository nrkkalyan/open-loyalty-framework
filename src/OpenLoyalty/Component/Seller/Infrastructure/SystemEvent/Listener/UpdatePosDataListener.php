<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Infrastructure\SystemEvent\Listener;

use OpenLoyalty\Component\Pos\Domain\SystemEvent\PosUpdatedSystemEvent;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsRepository;

/**
 * Class UpdatePosDataListener.
 */
class UpdatePosDataListener
{
    /**
     * @var SellerDetailsRepository
     */
    protected $sellerDetailsRepository;

    /**
     * UpdatePosDataListener constructor.
     *
     * @param SellerDetailsRepository $sellerDetailsRepository
     */
    public function __construct(SellerDetailsRepository $sellerDetailsRepository)
    {
        $this->sellerDetailsRepository = $sellerDetailsRepository;
    }

    public function handlePosUpdated(PosUpdatedSystemEvent $event)
    {
        $sellers = $this->sellerDetailsRepository->findBy(['posId' => $event->getPosId()->__toString()]);

        /** @var SellerDetails $seller */
        foreach ($sellers as $seller) {
            $seller->setPosName($event->getPosName());
            $seller->setPosCity($event->getPosCity());
            $this->sellerDetailsRepository->save($seller);
        }
    }
}
