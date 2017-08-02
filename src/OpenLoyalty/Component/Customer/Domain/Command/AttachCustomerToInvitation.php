<?php

namespace OpenLoyalty\Component\Customer\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\InvitationId;

/**
 * Class AttachCustomerToInvitation.
 */
class AttachCustomerToInvitation extends InvitationCommand
{
    /**
     * @var CustomerId
     */
    private $customerId;

    public function __construct(InvitationId $invitationId, CustomerId $customerId)
    {
        parent::__construct($invitationId);
        $this->customerId = $customerId;
    }

    /**
     * @return CustomerId
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }
}
