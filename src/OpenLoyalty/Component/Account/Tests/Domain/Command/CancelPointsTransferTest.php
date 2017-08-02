<?php

namespace OpenLoyalty\Component\Account\Tests\Domain\Command;

use OpenLoyalty\Component\Account\Domain\AccountId;
use OpenLoyalty\Component\Account\Domain\Command\CancelPointsTransfer;
use OpenLoyalty\Component\Account\Domain\Event\AccountWasCreated;
use OpenLoyalty\Component\Account\Domain\Event\PointsTransferHasBeenCanceled;
use OpenLoyalty\Component\Account\Domain\Event\PointsWereAdded;
use OpenLoyalty\Component\Account\Domain\Model\AddPointsTransfer;
use OpenLoyalty\Component\Account\Domain\PointsTransferId;
use OpenLoyalty\Component\Account\Domain\CustomerId;

/**
 * Class CancelPointsTransferTest.
 */
class CancelPointsTransferTest extends AccountCommandHandlerTest
{
    /**
     * @test
     */
    public function it_cancels_points()
    {
        $accountId = new AccountId('00000000-0000-0000-0000-000000000000');
        $customerId = new CustomerId('00000000-1111-0000-0000-000000000000');
        $pointsTransferId = new PointsTransferId('00000000-1111-0000-0000-000000000111');
        $this->scenario
            ->withAggregateId($accountId)
            ->given([
                new AccountWasCreated($accountId, $customerId),
                new PointsWereAdded($accountId, new AddPointsTransfer($pointsTransferId, 100))
            ])
            ->when(new CancelPointsTransfer($accountId, $pointsTransferId))
            ->then(array(
                new PointsTransferHasBeenCanceled($accountId, $pointsTransferId)
            ));
    }
}
