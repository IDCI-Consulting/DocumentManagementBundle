<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Functional\Controller\Api\Rest;

use IDCI\Bundle\DocumentManagementBundle\Tests\Functional\DocumentManagementWebTestCase;

class TemplateControllerTest extends DocumentManagementWebTestCase
{
    public function testGetTemplatesAction()
    {
        $this->client->request('GET', '/api/templates');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
