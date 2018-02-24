<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Fixtures\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

class LoadTemplateData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $template1 = new Template();
        $template1
            ->setName('Template name')
            ->setDescription('Template description.')
            ->setHtml('<html></html>')
            ->setCss('html { background: red; }');

        $template2 = clone $template1;

        $manager->persist($template1);
        $manager->persist($template2);

        $manager->flush();
    }
}
