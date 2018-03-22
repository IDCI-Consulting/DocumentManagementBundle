<?php

/**
 * @license: MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConverterCompilerPass
 */
class ConverterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_document.converter.registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('idci_document.converter.registry');
        foreach ($container->findTaggedServiceIds('idci_document.converter') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $id;

                $registryDefinition->addMethodCall(
                    'setConverter',
                    array($alias, new Reference($id))
                );
            }
        }
    }
}
