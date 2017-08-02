<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Email\Domain\ReadModel;

use OpenLoyalty\Component\Email\Domain\EmailId;

/**
 * Interface DoctrineEmailRepositoryInterface.
 */
interface DoctrineEmailRepositoryInterface
{
    /**
     * @return array
     */
    public function getAll();

    /**
     * @param EmailId $emailId
     *
     * @return Email
     */
    public function getById(EmailId $emailId);

    /**
     * @param $key
     *
     * @return Email
     */
    public function getByKey($key);
}
