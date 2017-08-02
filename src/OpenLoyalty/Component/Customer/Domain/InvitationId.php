<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain;

use Assert\Assertion;
use OpenLoyalty\Component\Core\Domain\Model\Identifier;

/**
 * Class InvitationId.
 */
class InvitationId implements Identifier
{
    /**
     * @var string
     */
    private $invitationId;

    /**
     * InvitationId constructor.
     *
     * @param string $invitationId
     */
    public function __construct($invitationId)
    {
        Assertion::string($invitationId);
        Assertion::uuid($invitationId);

        $this->invitationId = $invitationId;
    }

    public function __toString()
    {
        return $this->invitationId;
    }
}
