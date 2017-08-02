<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\Command;

use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventDispatcher\EventDispatcherInterface;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\EventStoreInterface;
use OpenLoyalty\Component\Customer\Domain\Command\InvitationCommandHandler;
use OpenLoyalty\Component\Customer\Domain\InvitationRepository;
use OpenLoyalty\Component\Customer\Domain\Service\InvitationTokenGenerator;

/**
 * Class InvitationCommandHandlerTest.
 */
abstract class InvitationCommandHandlerTest extends CommandHandlerScenarioTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createCommandHandler(EventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        $tokenGenerator = $this->getMockBuilder(InvitationTokenGenerator::class)->disableOriginalConstructor()
            ->getMock();
        $tokenGenerator->method('generate')->willReturn('123');
        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->disableOriginalConstructor()
            ->getMock();

        return new InvitationCommandHandler(
            new InvitationRepository($eventStore, $eventBus),
            $tokenGenerator,
            $dispatcher
        );
    }

}
