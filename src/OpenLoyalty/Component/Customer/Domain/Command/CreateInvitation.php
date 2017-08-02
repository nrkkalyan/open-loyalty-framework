<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\InvitationId;

/**
 * Class CreateInvitation.
 */
class CreateInvitation extends InvitationCommand
{
    /**
     * @var CustomerId
     */
    private $referrerId;

    /**
     * @var string
     */
    private $recipientEmail;

    public function __construct(InvitationId $invitationId, CustomerId $referrerId, $recipientEmail)
    {
        parent::__construct($invitationId);
        $this->referrerId = $referrerId;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * @return CustomerId
     */
    public function getReferrerId()
    {
        return $this->referrerId;
    }

    /**
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }
}
