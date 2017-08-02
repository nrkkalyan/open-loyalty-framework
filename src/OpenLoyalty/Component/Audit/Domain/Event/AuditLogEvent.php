<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Audit\Domain\Event;

use OpenLoyalty\Component\Audit\Domain\AuditLogId;

/**
 * Class AuditLogEvent.
 */
abstract class AuditLogEvent
{
    /**
     * @var AuditLogId
     */
    protected $auditLogId;

    /**
     * AuditLogEvent constructor.
     *
     * @param AuditLogId $auditLogId
     */
    public function __construct(AuditLogId $auditLogId)
    {
        $this->auditLogId = $auditLogId;
    }

    /**
     * @return AuditLogId
     */
    public function getAuditLogId(): AuditLogId
    {
        return $this->auditLogId;
    }
}
