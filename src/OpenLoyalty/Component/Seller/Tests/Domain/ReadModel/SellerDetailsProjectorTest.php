<?php

namespace OpenLoyalty\Component\Seller\Tests\Domain\ReadModel;

use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Testing\ProjectorScenarioTestCase;
use OpenLoyalty\Component\Pos\Domain\PosRepository;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasActivated;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasDeactivated;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasDeleted;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasRegistered;
use OpenLoyalty\Component\Seller\Domain\Event\SellerWasUpdated;
use OpenLoyalty\Component\Seller\Domain\PosId;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsProjector;
use OpenLoyalty\Component\Seller\Domain\SellerId;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class SellerDetailsProjectorTest.
 */
class SellerDetailsProjectorTest extends ProjectorScenarioTestCase
{
    /**
     * @param InMemoryRepository $repository
     *
     * @return Projector
     */
    protected function createProjector(InMemoryRepository $repository)
    {
        /** @var PosRepository|PHPUnit_Framework_MockObject_MockObject $posRepo */
        $posRepo = $this->getMockBuilder(PosRepository::class)->getMock();
        $posRepo->method('findBy')->willReturn(null);

        return new SellerDetailsProjector($repository, $posRepo);
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_register()
    {
        $sellerId = new SellerId('00000000-0000-0000-0000-000000000000');
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'open@loyalty.com',
            'phone' => '123456789',
            'posId' => (new PosId('00000000-0000-0000-0000-000000000000'))->__toString(),
            'createdAt' => new \DateTime(),
            'sellerId' => $sellerId->__toString(),
        ];

        $expectedReadModel = SellerDetails::deserialize($data);
        $this->scenario->given(array())
            ->when(new SellerWasRegistered($sellerId, $data))
            ->then(array(
                $expectedReadModel,
            ));
    }

    /**
     * @test
     */
    public function it_activates_seller()
    {
        $sellerId = new SellerId('00000000-0000-0000-0000-000000000000');
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'open@loyalty.com',
            'phone' => '123456789',
            'posId' => (new PosId('00000000-0000-0000-0000-000000000000'))->__toString(),
            'createdAt' => new \DateTime(),
            'sellerId' => $sellerId->__toString(),
        ];

        /** @var SellerDetails $expectedReadModel */
        $expectedReadModel = SellerDetails::deserialize($data);
        $expectedReadModel->setActive(true);
        $this->scenario
            ->given(array(
                new SellerWasRegistered($sellerId, $data),
            ))
            ->when(new SellerWasActivated($sellerId))
            ->then(array(
                $expectedReadModel,
            ));
    }

    /**
     * @test
     */
    public function it_deactivates_seller()
    {
        $sellerId = new SellerId('00000000-0000-0000-0000-000000000000');
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'open@loyalty.com',
            'phone' => '123456789',
            'posId' => (new PosId('00000000-0000-0000-0000-000000000000'))->__toString(),
            'createdAt' => new \DateTime(),
            'sellerId' => $sellerId->__toString(),
        ];

        /** @var SellerDetails $expectedReadModel */
        $expectedReadModel = SellerDetails::deserialize($data);
        $expectedReadModel->setActive(false);
        $this->scenario
            ->given(array(
                new SellerWasRegistered($sellerId, $data),
                new SellerWasActivated($sellerId),
            ))
            ->when(new SellerWasDeactivated($sellerId))
            ->then(array(
                $expectedReadModel,
            ));
    }

    /**
     * @test
     */
    public function it_deletes_seller()
    {
        $sellerId = new SellerId('00000000-0000-0000-0000-000000000000');
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'open@loyalty.com',
            'phone' => '123456789',
            'posId' => (new PosId('00000000-0000-0000-0000-000000000000'))->__toString(),
            'createdAt' => new \DateTime(),
            'sellerId' => $sellerId->__toString(),
        ];

        /** @var SellerDetails $expectedReadModel */
        $expectedReadModel = SellerDetails::deserialize($data);
        $expectedReadModel->setDeleted(true);
        $this->scenario
            ->given(array(
                new SellerWasRegistered($sellerId, $data),
            ))
            ->when(new SellerWasDeleted($sellerId))
            ->then(array(
                $expectedReadModel,
            ));
    }

    /**
     * @test
     */
    public function it_updates_seller()
    {
        $sellerId = new SellerId('00000000-0000-0000-0000-000000000000');
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'open@loyalty.com',
            'phone' => '123456789',
            'posId' => (new PosId('00000000-0000-0000-0000-000000000000'))->__toString(),
            'createdAt' => new \DateTime(),
            'sellerId' => $sellerId->__toString(),
        ];

        /** @var SellerDetails $expectedReadModel */
        $expectedReadModel = SellerDetails::deserialize($data);
        $expectedReadModel->setLastName('Kowalski');
        $this->scenario
            ->given(array(
                new SellerWasRegistered($sellerId, $data),
            ))
            ->when(new SellerWasUpdated($sellerId, ['lastName' => 'Kowalski']))
            ->then(array(
                $expectedReadModel,
            ));
    }
}
