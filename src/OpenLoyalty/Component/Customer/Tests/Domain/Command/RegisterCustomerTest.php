<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\Command;

use Broadway\EventDispatcher\EventDispatcherInterface;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\InMemoryEventStore;
use Broadway\EventStore\TraceableEventStore;
use OpenLoyalty\Component\Customer\Domain\Command\RegisterCustomer;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerWasRegistered;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\SystemEvent\CustomerSystemEvents;

/**
 * Class RegisterCustomerTest.
 */
class RegisterCustomerTest extends CustomerCommandHandlerTest
{
    /**
     * @test
     */
    public function it_registers_new_customer()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');
        $this->scenario
            ->withAggregateId($customerId)
            ->given([])
            ->when(new RegisterCustomer($customerId, CustomerCommandHandlerTest::getCustomerData()))
            ->then(array(
                new CustomerWasRegistered($customerId, CustomerCommandHandlerTest::getCustomerData()),
            ));
    }

    /**
     * @test
     */
    public function it_dispatch_event_on_register()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');

        $eventStore = new TraceableEventStore(new InMemoryEventStore());

        $eventBus = new SimpleEventBus();
        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(CustomerSystemEvents::CUSTOMER_REGISTERED))
            ->willReturn(true);
        $handler = $this->getCustomerCommandHandler($eventStore, $eventBus, $eventDispatcher);
        $handler->handle(new RegisterCustomer($customerId, CustomerCommandHandlerTest::getCustomerData()));
    }
}
