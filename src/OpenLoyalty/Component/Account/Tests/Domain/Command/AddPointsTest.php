<?php

namespace OpenLoyalty\Component\Account\Tests\Domain\Command;

use OpenLoyalty\Component\Account\Domain\AccountId;
use OpenLoyalty\Component\Account\Domain\Command\AddPoints;
use OpenLoyalty\Component\Account\Domain\Event\AccountWasCreated;
use OpenLoyalty\Component\Account\Domain\Event\PointsWereAdded;
use OpenLoyalty\Component\Account\Domain\Model\AddPointsTransfer;
use OpenLoyalty\Component\Account\Domain\PointsTransferId;
use OpenLoyalty\Component\Account\Domain\CustomerId;

/**
 * Class AddPointsTest.
 */
class AddPointsTest extends AccountCommandHandlerTest
{
    /**
     * @test
     */
    public function it_add_points_to_account()
    {
        $accountId = new AccountId('00000000-0000-0000-0000-000000000000');
        $customerId = new CustomerId('00000000-1111-0000-0000-000000000000');
        $pointsTransferId = new PointsTransferId('00000000-1111-0000-0000-000000000111');
        $this->scenario
            ->withAggregateId($accountId)
            ->given([
                new AccountWasCreated($accountId, $customerId)
            ])
            ->when(new AddPoints($accountId, new AddPointsTransfer($pointsTransferId, 100)))
            ->then(array(
                new PointsWereAdded($accountId, new AddPointsTransfer($pointsTransferId, 100))
            ));
    }
}
