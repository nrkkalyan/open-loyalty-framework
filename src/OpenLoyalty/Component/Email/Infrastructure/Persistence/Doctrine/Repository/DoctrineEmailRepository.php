<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Email\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use OpenLoyalty\Component\Email\Domain\Email;
use OpenLoyalty\Component\Email\Domain\EmailId;
use OpenLoyalty\Component\Email\Domain\EmailRepositoryInterface;

/**
 * Class DoctrineEmailRepository.
 */
class DoctrineEmailRepository extends EntityRepository implements EmailRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getById(EmailId $emailId)
    {
        return $this->find($emailId->__toString());
    }

    /**
     * {@inheritdoc}
     */
    public function getByKey($key)
    {
        return $this->findOneBy(['key' => $key]);
    }

    /**
     * {@inheritdoc}
     */
    public function save(Email $email)
    {
        $this->getEntityManager()->persist($email);
        $this->getEntityManager()->flush();
    }
}
