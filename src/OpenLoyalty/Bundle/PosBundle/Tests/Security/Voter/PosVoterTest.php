<?php

namespace OpenLoyalty\Bundle\PosBundle\Tests\Security\Voter;

use OpenLoyalty\Bundle\BaseVoterTest;
use OpenLoyalty\Bundle\PosBundle\Security\Voter\PosVoter;
use OpenLoyalty\Component\Pos\Domain\Pos;
use OpenLoyalty\Component\Pos\Domain\PosId;

/**
 * Class PosVoterTest.
 */
class PosVoterTest extends BaseVoterTest
{
    const POS_ID = '00000000-0000-474c-b092-b0dd880c0700';

    /**
     * @test
     */
    public function it_works()
    {
        $attributes = [
            PosVoter::LIST_POS => ['seller' => true, 'customer' => false, 'admin' => true],
            PosVoter::CREATE_POS => ['seller' => false, 'customer' => false, 'admin' => true],
            PosVoter::EDIT => ['seller' => false, 'customer' => false, 'admin' => true, 'id' => self::POS_ID],
            PosVoter::VIEW => ['seller' => true, 'customer' => false, 'admin' => true, 'id' => self::POS_ID],
        ];

        $voter = new PosVoter();

        $this->makeAssertions($attributes, $voter);
    }

    protected function getSubjectById($id)
    {
        $level = $this->getMockBuilder(Pos::class)->disableOriginalConstructor()->getMock();
        $level->method('getPosId')->willReturn(new PosId($id));

        return $level;
    }
}
