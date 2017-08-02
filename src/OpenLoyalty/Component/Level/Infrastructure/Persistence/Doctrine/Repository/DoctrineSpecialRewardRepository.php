<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Level\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use OpenLoyalty\Component\Level\Domain\SpecialReward;
use OpenLoyalty\Component\Level\Domain\SpecialRewardId;
use OpenLoyalty\Component\Level\Domain\SpecialRewardRepository;

/**
 * Class DoctrineSpecialRewardRepository.
 */
class DoctrineSpecialRewardRepository extends EntityRepository implements SpecialRewardRepository
{
    public function byId(SpecialRewardId $specialRewardId)
    {
        return parent::find($specialRewardId);
    }

    public function findAll()
    {
        return parent::findAll();
    }

    public function save(SpecialReward $specialReward)
    {
        $this->getEntityManager()->persist($specialReward);
        $this->getEntityManager()->flush();
    }

    public function remove(SpecialReward $specialReward)
    {
        $this->getEntityManager()->remove($specialReward);
    }
}
