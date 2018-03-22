<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array();

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\FrameworkBundle\FrameworkBundle();
            $bundles[] = new Symfony\Bundle\TwigBundle\TwigBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Doctrine\Bundle\DoctrineBundle\DoctrineBundle();
            $bundles[] = new JMS\SerializerBundle\JMSSerializerBundle();
            $bundles[] = new FOS\RestBundle\FOSRestBundle();
            $bundles[] = new IDCI\Bundle\DocumentManagementBundle\IDCIDocumentManagementBundle();
            $bundles[] = new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle();
            $bundles[] = new Knp\Bundle\SnappyBundle\KnpSnappyBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }
}
