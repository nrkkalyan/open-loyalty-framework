<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyaltyPlugin\SalesManagoBundle\Service;

use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerAgreementsUpdatedSystemEvent;
use OpenLoyalty\Component\Segment\Domain\SystemEvent\CustomerAddedToSegmentSystemEvent;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;

/**
 * Class SalesManagoContactSender.
 */
class SalesManagoContactSegmentTagsSender extends SalesManagoContactSender
{
    /**
     * @param CustomerAddedToSegmentSystemEvent $data
     */
    public function customerSegmentAdd(CustomerAddedToSegmentSystemEvent $data)
    {
        if (empty($this->getConnector())) {
            return;
        }
        /* @var CustomerDetails $customer */
        $details = $this->dataProvider->provideData($data);

        $this->send($details['email'], $this->buildTag($details['tag']));
    }

    /**
     * @param CustomerAgreementsUpdatedSystemEvent $data
     */
    public function customerAgreementChanged(CustomerAgreementsUpdatedSystemEvent $data)
    {
        if (empty($this->getConnector())) {
            return;
        }

        $tags = $this->dataProvider->getAgreementTags($data);
        if ($tags) {
            $this->send($tags['email'], $tags['tag']);
        }
    }
}
