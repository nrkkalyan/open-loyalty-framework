<?php

namespace OpenLoyalty\Bundle\UserBundle\Tests\Controller;

use OpenLoyalty\Component\Customer\Domain\Customer;
use OpenLoyalty\Component\Customer\Domain\CustomerId;

/**
 * Class CustomerTest.
 */
class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_on_empty_first_name()
    {
        $customer = new Customer(new CustomerId('00-000-000-000'));
        $customer->setFirstName('');
    }
}
