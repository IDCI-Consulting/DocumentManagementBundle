<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Functional\Controller\Api\Rest;

use Symfony\Component\HttpFoundation\Response;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use IDCI\Bundle\DocumentManagementBundle\Tests\Functional\DocumentManagementWebTestCase;

class TemplateControllerTest extends DocumentManagementWebTestCase
{
    public function testGetTemplatesAction()
    {
        $this->client->request('GET', '/api/templates');

        $templates = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(3, sizeof($templates));
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetTemplateAction()
    {
        $this->client->request('GET', '/api/templates/b08c6fff-7dc5-e111-9b21-0800200c9a66');

        $template = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('b08c6fff-7dc5-e111-9b21-0800200c9a66', $template['id']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPostTemplateAction()
    {
        $params = array(
            'name' => 'Template four',
            'description' => 'Template description four',
            'html' => '<html></html>',
            'css' => 'html { background: tomato; }',
        );

        $this->client->request('POST', '/api/templates', $params);

        $template = $this
            ->container
            ->get('doctrine')
            ->getManager()
            ->getRepository(Template::class)
            ->findOneByName($params);

        $this->assertEquals($params['description'], $template->getDescription());
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testTemplateNotFound()
    {
        $this->client->request('GET', '/api/templates/dummy-id');

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEmpty($content);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
