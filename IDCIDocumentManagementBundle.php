<?php

namespace IDCI\Bundle\DocumentManagementBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IDCI\Bundle\DocumentManagementBundle\DependencyInjection\Compiler\ConverterCompilerPass;

class IDCIDocumentManagementBundle extends BaseBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConverterCompilerPass());
    }
}
