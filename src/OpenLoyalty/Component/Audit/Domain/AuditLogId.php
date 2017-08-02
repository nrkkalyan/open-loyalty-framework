<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Audit\Domain;

use Assert\Assertion;
use OpenLoyalty\Component\Core\Domain\Model\Identifier;

/**
 * Class AuditLogId.
 */
class AuditLogId implements Identifier
{
    /**
     * @var string
     */
    private $auditLogId;

    /**
     * AuditLog constructor.
     *
     * @param string $auditLogId
     */
    public function __construct($auditLogId)
    {
        Assertion::string($auditLogId);
        Assertion::uuid($auditLogId);

        $this->auditLogId = $auditLogId;
    }

    public function __toString()
    {
        return $this->auditLogId;
    }
}
