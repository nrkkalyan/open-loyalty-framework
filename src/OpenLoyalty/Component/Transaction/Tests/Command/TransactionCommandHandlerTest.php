<?php

namespace OpenLoyalty\Component\Transaction\Tests\Command;

use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\EventStoreInterface;
use OpenLoyalty\Component\Transaction\Domain\Command\TransactionCommandHandler;
use OpenLoyalty\Component\Transaction\Domain\TransactionRepository;

/**
 * Class TransactionCommandHandlerTest.
 */
abstract class TransactionCommandHandlerTest extends CommandHandlerScenarioTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createCommandHandler(EventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        return new TransactionCommandHandler(
            new TransactionRepository($eventStore, $eventBus)
        );
    }

}
