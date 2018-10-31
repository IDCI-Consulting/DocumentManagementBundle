<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Functional\Controller\Api\Rest;

use Symfony\Component\HttpFoundation\Response;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;
use IDCI\Bundle\DocumentManagementBundle\Tests\Functional\DocumentManagementWebTestCase;

class DocumentControllerTest extends DocumentManagementWebTestCase
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $manager;

    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->container->get('doctrine')->getManager();
    }

    public function testGetDocumentsAction()
    {
        $this->client->request('GET', '/api/documents', array(
            'reference' => 'reference-one',
        ));

        $response = $this->client->getResponse();

        $documents = json_decode($response->getContent(), true);

        $this->assertEquals(3, sizeof($documents));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetDocumentAction()
    {
        $this->client->request('GET', '/api/documents/af4bc160-2385-11e8-b467-0ed5f89f718b');
        $response = $this->client->getResponse();

        $document = json_decode($response->getContent(), true);

        $this->assertEquals('af4bc160-2385-11e8-b467-0ed5f89f718b', $document['id']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostDocumentAction()
    {
        $params = array(
            'name' => 'Document four',
            'description' => 'Document description four',
            'data' => json_encode(array('firstname' => 'Dummy')),
            'reference' => 'document-four',
            'format' => 'pdf',
            'template' => 'template-slug',
        );

        $this->client->request('POST', '/api/documents', $params);
        $response = $this->client->getResponse();

        $document = $this
            ->manager
            ->getRepository(Document::class)
            ->findOneByReference($params['reference']);

        $this->assertEquals($params['description'], $document->getDescription());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPatchDocumentAction()
    {
        $params = array(
            'reference' => 'document-four',
        );

        $this->client->request('PATCH', '/api/documents/af4bc160-2385-11e8-b467-0ed5f89f718b', $params);
        $response = $this->client->getResponse();

        $document = $this
            ->manager
            ->getRepository(Document::class)
            ->find('af4bc160-2385-11e8-b467-0ed5f89f718b');

        $this->assertEquals($params['reference'], $document->getReference());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteDocumentAction()
    {
        $templates = $this
            ->manager
            ->getRepository(Document::class)
            ->findAll();

        $this->client->request('DELETE', '/api/documents/af4bc160-2385-11e8-b467-0ed5f89f718b');

        $documents = $this
            ->manager
            ->getRepository(Document::class)
            ->findAll();

        $this->assertEquals(2, sizeof($documents));
        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testDocumentNotFound()
    {
        $this->client->request('GET', '/api/documents/dummy-id');
        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
