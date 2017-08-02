<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Segment\Infrastructure\Repository;

use OpenLoyalty\Component\Core\Infrastructure\Repository\OloyElasticsearchRepository;
use OpenLoyalty\Component\Segment\Domain\ReadModel\SegmentedCustomersRepository;

/**
 * Class SegmentedCustomersElasticsearchRepository.
 */
class SegmentedCustomersElasticsearchRepository extends OloyElasticsearchRepository implements SegmentedCustomersRepository
{
}
