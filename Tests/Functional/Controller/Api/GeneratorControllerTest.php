<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Functional\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environnement;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;
use IDCI\Bundle\DocumentManagementBundle\Tests\Functional\DocumentManagementWebTestCase;

class GeneratorControllerTest extends DocumentManagementWebTestCase
{
    public function testGenerateDocument()
    {
        $this->client->request('GET', '/api/documents/af4bc160-2385-11e8-b467-0ed5f89f718b/generate');
        $response = $this->client->getResponse();

        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function testGenerateDocumentFromTemplate()
    {
        $params = array(
            'data' => json_encode(array('firstname' => 'dummy')),
        );

        $this->client->request('GET', '/api/templates/b08c6fff-7dc5-e111-9b21-0800200c9a66/generate', $params);
        $response = $this->client->getResponse();

        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function testGenerateDocumentFromTemplateBySlug()
    {
        $params = array(
            'data' => json_encode(array('firstname' => 'dummy')),
        );

        $this->client->request('GET', '/api/templates/template-slug/generate', $params);
        $response = $this->client->getResponse();

        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
