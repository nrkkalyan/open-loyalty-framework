<?php

namespace OpenLoyalty\Component\Customer\Domain\ReadModel;

use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Testing\ProjectorScenarioTestCase;
use OpenLoyalty\Component\Account\Domain\AccountId;
use OpenLoyalty\Component\Account\Domain\Event\AccountWasCreated;
use OpenLoyalty\Component\Account\Domain\ReadModel\AccountDetails;
use OpenLoyalty\Component\Account\Domain\ReadModel\AccountDetailsProjector;
use OpenLoyalty\Component\Account\Domain\CustomerId;

/**
 * Class AccountDetailsProjectorTest.
 */
class AccountDetailsProjectorTest extends ProjectorScenarioTestCase
{
    /**
     * @var AccountId
     */
    protected $accountId;

    /**
     * @var CustomerId
     */
    protected $customerId;

    /**
     * {@inheritDoc}
     */
    protected function createProjector(InMemoryRepository $repository)
    {
        $this->accountId = new AccountId('00000000-0000-0000-0000-000000000000');
        $this->customerId = new CustomerId('00000000-1111-0000-0000-000000000000');

        return new AccountDetailsProjector($repository);
    }

    /**
     * @test
     */
    public function it_creates_a_read_model()
    {
        $this->scenario->given(array())
            ->when(new AccountWasCreated($this->accountId, $this->customerId))
            ->then(array(
                $this->createReadModel()
            ));
    }

    private function createReadModel()
    {
        return new AccountDetails($this->accountId, $this->customerId);
    }
}
