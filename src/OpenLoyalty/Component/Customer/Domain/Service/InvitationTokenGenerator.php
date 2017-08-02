<?php

namespace OpenLoyalty\Component\Customer\Domain\Service;

use OpenLoyalty\Component\Customer\Domain\CustomerId;

interface InvitationTokenGenerator
{
    /**
     * @param CustomerId $referrerId
     * @param string     $recipientEmail
     *
     * @return string
     */
    public function generate(CustomerId $referrerId, $recipientEmail);
}
