<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace OpenLoyalty\Bundle\EarningRuleBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use OpenLoyalty\Bundle\EarningRuleBundle\DependencyInjection\Compiler\EarningRuleAlgorithmFactoryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenLoyaltyEarningRuleBundle
 */
class OpenLoyaltyEarningRuleBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EarningRuleAlgorithmFactoryPass());
        $container->addCompilerPass($this->buildMappingCompilerPass());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [__DIR__.'/../../Component/EarningRule/Infrastructure/Persistence/Doctrine/ORM' => 'OpenLoyalty\Component\EarningRule\Domain'],
            [],
            false,
            ['OpenLoyaltyEarningRule' => 'OpenLoyalty\Component\EarningRule\Domain']

        );
    }
}
