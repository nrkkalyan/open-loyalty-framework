<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\InvitationId;

/**
 * Class InvitationCommand.
 */
abstract class InvitationCommand
{
    /**
     * @var InvitationId
     */
    protected $invitationId;

    /**
     * InvitationCommand constructor.
     *
     * @param InvitationId $invitationId
     */
    public function __construct(InvitationId $invitationId)
    {
        $this->invitationId = $invitationId;
    }

    /**
     * @return InvitationId
     */
    public function getInvitationId()
    {
        return $this->invitationId;
    }
}
