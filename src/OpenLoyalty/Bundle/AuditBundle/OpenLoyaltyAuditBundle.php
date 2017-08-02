<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace OpenLoyalty\Bundle\AuditBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use OpenLoyalty\Bundle\AuditBundle\DependencyInjection\Compiler\AuditableCommandBusCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenLoyaltyAuditBundle
 */
class OpenLoyaltyAuditBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AuditableCommandBusCompilerPass());
        $container->addCompilerPass($this->buildMappingCompilerPass());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [__DIR__.'/../../Component/Audit/Infrastructure/Persistence/Doctrine/ORM' => 'OpenLoyalty\Component\Audit\Domain'],
            [],
            false,
            ['OpenLoyaltyAudit' => 'OpenLoyalty\Component\Audit\Domain']

        );
    }
}
