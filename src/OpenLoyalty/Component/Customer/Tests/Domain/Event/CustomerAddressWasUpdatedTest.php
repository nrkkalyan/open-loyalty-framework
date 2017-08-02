<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\Event;

use Broadway\Serializer\Testing\SerializableEventTestCase;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerAddressWasUpdated;
use OpenLoyalty\Component\Customer\Tests\Domain\Command\CustomerCommandHandlerTest;
use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class CustomerAddressWasUpdatedTest.
 */
class CustomerAddressWasUpdatedTest extends SerializableEventTestCase
{
    const USER_ID = '00000000-0000-0000-0000-000000000000';

    public function getters_of_event_works()
    {
        $event = $this->createEvent();

        $this->assertEquals(static::USER_ID, $event->getCustomerId());
        $this->assertEquals(CustomerCommandHandlerTest::getCustomerData()['address'], $event->getAddressData());
    }
    /**
     * @return CustomerAddressWasUpdated
     */
    protected function createEvent()
    {
        return new CustomerAddressWasUpdated(new CustomerId(static::USER_ID), CustomerCommandHandlerTest::getCustomerData()['address']);
    }
}
