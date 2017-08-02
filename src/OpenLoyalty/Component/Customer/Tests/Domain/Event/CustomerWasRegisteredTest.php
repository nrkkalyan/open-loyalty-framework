<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\Event;

use Broadway\Serializer\Testing\SerializableEventTestCase;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerWasRegistered;
use OpenLoyalty\Component\Customer\Tests\Domain\Command\CustomerCommandHandlerTest;
use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class CustomerWasRegisteredTest.
 *
 * @package OpenLoyalty\Domain\User\Event
 */
class CustomerWasRegisteredTest extends SerializableEventTestCase
{
    const USER_ID = '00000000-0000-0000-0000-000000000000';

    public function getters_of_event_works()
    {
        $event = $this->createEvent();

        $this->assertEquals(static::USER_ID, $event->getCustomerId());
        $data = $event->getCustomerData();
        unset($data['createdAt']);
        $this->assertEquals(CustomerCommandHandlerTest::getCustomerData(), $data);
    }
    /**
     * @return CustomerWasRegistered
     */
    protected function createEvent()
    {
        return new CustomerWasRegistered(new CustomerId(static::USER_ID), CustomerCommandHandlerTest::getCustomerData());
    }
}
