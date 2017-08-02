<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Audit\Domain;

use OpenLoyalty\Component\Audit\Domain\Model\AuditLogSearchCriteria;

/**
 * Interface AuditLogRepository.
 */
interface AuditLogRepository
{
    public function byId(AuditLogId $auditLogId);

    public function findAll();

    public function save(AuditLog $auditLog);

    public function remove(AuditLog $auditLog);

    public function countTotal(AuditLogSearchCriteria $criteria);

    public function findAllPaginated(AuditLogSearchCriteria $criteria, $page = 1, $perPage = 10, $sortField = null, $direction = 'ASC');
}
