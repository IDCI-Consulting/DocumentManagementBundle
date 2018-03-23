<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Functional\Controller\Api\Rest;

use Symfony\Component\HttpFoundation\Response;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use IDCI\Bundle\DocumentManagementBundle\Tests\Functional\DocumentManagementWebTestCase;

class TemplateControllerTest extends DocumentManagementWebTestCase
{
    /**
     * @var Doctrine\ORM\EntityManager $manager
     */
    private $manager;

    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->container->get('doctrine')->getManager();
    }

    public function testGetTemplatesAction()
    {
        $this->client->request('GET', '/api/templates');
        $response = $this->client->getResponse();

        $templates = json_decode($response->getContent(), true);

        $this->assertEquals(3, sizeof($templates));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetTemplateAction()
    {
        $this->client->request('GET', '/api/templates/b08c6fff-7dc5-e111-9b21-0800200c9a66');
        $response = $this->client->getResponse();

        $template = json_decode($response->getContent(), true);

        $this->assertEquals('b08c6fff-7dc5-e111-9b21-0800200c9a66', $template['id']);
        $this->assertEquals('template-slug', $template['slug']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteTemplateAction()
    {
        $templates = $this
            ->manager
            ->getRepository(Template::class)
            ->findAll();

        $this->client->request('DELETE', '/api/templates/b08c6fff-7dc5-e111-9b21-0800200c9a66');

        $templates = $this
            ->manager
            ->getRepository(Template::class)
            ->findAll();

        $this->assertEquals(2, sizeof($templates));
        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testTemplateNotFound()
    {
        $this->client->request('GET', '/api/templates/dummy-id');
        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
