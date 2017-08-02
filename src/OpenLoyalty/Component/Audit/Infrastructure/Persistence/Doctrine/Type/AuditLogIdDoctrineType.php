<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Audit\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use OpenLoyalty\Component\Audit\Domain\AuditLogId;
use Rhumsaa\Uuid\Doctrine\UuidType;

/**
 * Class AuditLogIdDoctrineType.
 */
final class AuditLogIdDoctrineType extends UuidType
{
    /**
     *
     */
    const NAME = 'audit_log_id';

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return;
        }

        if ($value instanceof AuditLogId) {
            return $value;
        }

        return new AuditLogId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof AuditLogId) {
            return $value->__toString();
        }

        return;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
