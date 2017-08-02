<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use OpenLoyalty\Component\Segment\Domain\Model\SegmentPart;
use OpenLoyalty\Component\Segment\Domain\SegmentPartId;
use OpenLoyalty\Component\Segment\Domain\SegmentPartRepository;

/**
 * Class DoctrineSegmentPartRepository.
 */
class DoctrineSegmentPartRepository extends EntityRepository implements SegmentPartRepository
{
    public function byId(SegmentPartId $segmentPartId)
    {
        return parent::find($segmentPartId);
    }

    public function findAll()
    {
        return parent::findAll();
    }

    public function save(SegmentPart $segmentPart)
    {
        $this->getEntityManager()->persist($segmentPart);
        $this->getEntityManager()->flush($segmentPart);
    }

    public function remove(SegmentPart $segmentPart)
    {
        $this->getEntityManager()->remove($segmentPart);
        $this->getEntityManager()->flush();
    }
}
