<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Fixtures\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

class LoadTemplateData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $template1 = new Template();
        $template1
            ->setName('Template one')
            ->setDescription('Template one description.')
            ->setHtml('Hello world')
            ->setCss('html { background: red; }');

        $template2 = new Template();
        $template2
            ->setName('Template two')
            ->setDescription('Template two description.')
            ->setHtml('Hello world')
            ->setCss('html { background: blue; }');

        $this->loadThirdTemplate($manager);

        $manager->persist($template1);
        $manager->persist($template2);

        $manager->flush();
    }

    /**
     * Load the third template.
     * While using Uuid generation, it is necessary to create
     * a third template entity manually to avoid id generation.
     *
     * @param ObjectManager $manager
     */
    private function loadThirdTemplate(ObjectManager $manager)
    {
        $now = new \Datetime('now');
        $sql = "INSERT INTO template VALUES (:id, :name, :slug, :description, :html, :css, :created_at, :updated_at);";

        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute(array(
            'id' => Uuid::fromString('b08c6fff-7dc5-e111-9b21-0800200c9a66'),
            'name' => 'Template three',
            'slug' => 'template-slug',
            'description' => 'Template three description',
            'html' => 'Hello {{ data.firstname }}',
            'css' => 'html { background: green; }',
            'created_at' => $now->format('Ymd'),
            'updated_at' => $now->format('Ymd'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
