<?php

namespace OpenLoyalty\Component\Customer\Domain\Event;

use OpenLoyalty\Component\Customer\Domain\InvitationId;

/**
 * Class PurchaseWasMadeForThisInvitation.
 */
class PurchaseWasMadeForThisInvitation extends InvitationEvent
{
    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self(new InvitationId($data['invitationId']));
    }
}
