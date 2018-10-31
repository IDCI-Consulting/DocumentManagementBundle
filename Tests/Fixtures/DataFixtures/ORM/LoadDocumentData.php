<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Fixtures\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

class LoadDocumentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $template = $manager->getRepository(Template::class)->find('b08c6fff-7dc5-e111-9b21-0800200c9a66');

        $document1 = new Document();
        $document1
            ->setName('Document one')
            ->setDescription('Document one description.')
            ->setData(array(
                'firstname' => 'Foo',
            ))
            ->setReference('reference-one')
            ->setTemplate($template);

        $document2 = new Document();
        $document2
            ->setName('Document two')
            ->setDescription('Document two description.')
            ->setData(array(
                'firstname' => 'Bar',
            ))
            ->setReference('reference-one')
            ->setTemplate($template);

        $manager->persist($document1);
        $manager->persist($document2);

        $this->loadThirdDocument($manager, $template);

        $manager->flush();
    }

    /**
     * Add a third document.
     *
     * @param ObjectManager $manager
     * @param Template      $template
     */
    protected function loadThirdDocument(ObjectManager $manager, Template $template)
    {
        $now = new \Datetime('now');
        $sql = 'INSERT INTO document VALUES (:id, :template_id, :name, :description, :data, :format, :reference, :created_at, :updated_at);';

        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute(array(
            'id' => Uuid::fromString('af4bc160-2385-11e8-b467-0ed5f89f718b'),
            'template_id' => $template->getId(),
            'name' => 'Document three',
            'description' => 'Document three description',
            'data' => json_encode(array('firstname' => 'Acme')),
            'format' => 'pdf',
            'reference' => 'reference-one',
            'created_at' => $now->format('Ymd'),
            'updated_at' => $now->format('Ymd'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
