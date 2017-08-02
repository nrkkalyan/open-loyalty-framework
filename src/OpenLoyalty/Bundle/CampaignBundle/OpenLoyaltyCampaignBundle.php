<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace OpenLoyalty\Bundle\CampaignBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use OpenLoyalty\Bundle\CampaignBundle\DependencyInjection\OpenLoyaltyCampaignExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class OpenLoyaltyCampaignBundle
 */
class OpenLoyaltyCampaignBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass($this->buildMappingCompilerPass());
        $container->registerExtension(new OpenLoyaltyCampaignExtension());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [__DIR__.'/../../Component/Campaign/Infrastructure/Persistence/Doctrine/ORM' => 'OpenLoyalty\Component\Campaign\Domain'],
            [],
            false,
            ['OpenLoyaltyCampaign' => 'OpenLoyalty\Component\Campaign\Domain']

        );
    }
}
