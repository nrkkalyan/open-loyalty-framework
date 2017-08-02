<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\Command;

use OpenLoyalty\Component\Customer\Domain\Command\CreateInvitation;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\Event\InvitationWasCreated;
use OpenLoyalty\Component\Customer\Domain\InvitationId;

/**
 * Class CreateInvitationTest.
 */
class CreateInvitationTest extends InvitationCommandHandlerTest
{
    /**
     * @test
     */
    public function it_creates_new_invitation()
    {
        $invitationId = new InvitationId('00000000-0000-0000-0000-000000000000');
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000001');

        $this->scenario
            ->withAggregateId($invitationId)
            ->given([])
            ->when(new CreateInvitation($invitationId, $customerId, 'test@oloy.com'))
            ->then(array(
                new InvitationWasCreated($invitationId, $customerId, 'test@oloy.com', 123)
            ));
    }
}
