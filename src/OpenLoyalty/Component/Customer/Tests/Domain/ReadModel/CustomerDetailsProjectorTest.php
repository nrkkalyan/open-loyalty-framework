<?php

namespace OpenLoyalty\Component\Customer\Tests\Domain\ReadModel;

use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Testing\ProjectorScenarioTestCase;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetailsProjector;
use OpenLoyalty\Component\Customer\Tests\Domain\Command\CustomerCommandHandlerTest;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerAddressWasUpdated;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerCompanyDetailsWereUpdated;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerDetailsWereUpdated;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerLoyaltyCardNumberWasUpdated;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerWasDeactivated;
use OpenLoyalty\Component\Customer\Domain\Event\CustomerWasRegistered;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Transaction\Domain\ReadModel\TransactionDetailsRepository;

/**
 * Class CustomerDetailsProjectorTest.
 */
class CustomerDetailsProjectorTest extends ProjectorScenarioTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createProjector(InMemoryRepository $repository)
    {
        $transactionDetailsRepo = $this->getMockBuilder(TransactionDetailsRepository::class)->getMock();

        return new CustomerDetailsProjector($repository, $transactionDetailsRepo);
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_register()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');

        $this->scenario->given(array())
            ->when(new CustomerWasRegistered($customerId, CustomerCommandHandlerTest::getCustomerData()))
            ->then(array(
                $this->createBaseReadModel($customerId, CustomerCommandHandlerTest::getCustomerData()),
            ));
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_register_and_properly_sets_agreements()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');

        $data = CustomerCommandHandlerTest::getCustomerData();
        $data['agreement1'] = true;
        $data['agreement2'] = false;
        $data['agreement3'] = true;

        $this->scenario->given(array())
            ->when(new CustomerWasRegistered($customerId, $data))
            ->then(array(
                $this->createBaseReadModel($customerId, $data),
            ));
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_register_and_address_update()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');

        $customerLoyaltyCardNumberWasUpdated = new CustomerLoyaltyCardNumberWasUpdated($customerId,
            CustomerCommandHandlerTest::getCustomerData()['loyaltyCardNumber']);
        $data = CustomerCommandHandlerTest::getCustomerData();
        $data['updatedAt'] = $customerLoyaltyCardNumberWasUpdated->getUpdateAt()->getTimestamp();

        $this->scenario->given(array())
            ->when(new CustomerWasRegistered($customerId, CustomerCommandHandlerTest::getCustomerData()))
            ->when(new CustomerAddressWasUpdated($customerId, CustomerCommandHandlerTest::getCustomerData()['address']))
            ->when(new CustomerCompanyDetailsWereUpdated($customerId, CustomerCommandHandlerTest::getCustomerData()['company']))
            ->when($customerLoyaltyCardNumberWasUpdated)
            ->then(array(
                $this->createReadModel($customerId, $data),
            ));
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_register_and_deactivate()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');

        $data = CustomerCommandHandlerTest::getCustomerData();
        $data['active'] = false;
        $data['address'] = null;
        $data['loyaltyCardNumber'] = null;
        $data['company'] = null;

        $this->scenario->given(array())
            ->when(new CustomerWasRegistered($customerId, CustomerCommandHandlerTest::getCustomerData()))
            ->when(new CustomerWasDeactivated($customerId))
            ->then(array(
                $this->createReadModel($customerId, $data),
            ));
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_register_and_name_update()
    {
        $customerId = new CustomerId('00000000-0000-0000-0000-000000000000');

        $data = CustomerCommandHandlerTest::getCustomerData();
        $data['firstName'] = 'Jane';
        unset($data['company']);
        unset($data['loyaltyCardNumber']);
        $customerDetailsWereUpdated = new CustomerDetailsWereUpdated($customerId, ['firstName' => 'Jane']);
        $data['updatedAt'] = $customerDetailsWereUpdated->getUpdateAt()->getTimestamp();

        $this->scenario->given(array())
            ->when(new CustomerWasRegistered($customerId, CustomerCommandHandlerTest::getCustomerData()))
            ->when(new CustomerAddressWasUpdated($customerId, CustomerCommandHandlerTest::getCustomerData()['address']))
            ->when($customerDetailsWereUpdated)
            ->then(array(
                $this->createReadModel($customerId, $data),
            ));
    }

    private function createBaseReadModel(CustomerId $customerId, array $data)
    {
        $data['id'] = $customerId->__toString();
        unset($data['loyaltyCardNumber']);
        unset($data['company']);
        unset($data['address']);

        return CustomerDetails::deserialize($data);
    }

    private function createReadModel(CustomerId $customerId, array $data)
    {
        $data['id'] = $customerId->__toString();

        return CustomerDetails::deserialize($data);
    }
}
