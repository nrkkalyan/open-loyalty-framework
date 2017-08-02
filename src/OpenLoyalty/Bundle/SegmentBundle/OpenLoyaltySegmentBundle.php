<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace OpenLoyalty\Bundle\SegmentBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use OpenLoyalty\Bundle\SegmentBundle\DependencyInjection\CompilerPass\SegmentationEvaluatorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenLoyaltySegmentBundle
 */
class OpenLoyaltySegmentBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SegmentationEvaluatorCompilerPass());
        $container->addCompilerPass($this->buildMappingCompilerPass());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [__DIR__.'/../../Component/Segment/Infrastructure/Persistence/Doctrine/ORM' => 'OpenLoyalty\Component\Segment\Domain'],
            [],
            false,
            ['OpenLoyaltySegment' => 'OpenLoyalty\Component\Segment\Domain']
        );
    }
}
