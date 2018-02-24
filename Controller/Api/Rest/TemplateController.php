<?php

namespace IDCI\Bundle\DocumentManagementBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\Serializer\SerializationContext;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

class TemplateController extends FOSRestController
{
    public function getTemplatesAction()
    {
        $view = $this->view(
            $this->getDoctrine()->getManager()->getRepository(Template::class)->findAll(),
            Response::HTTP_OK
        );

        $context = SerializationContext::create()->setGroups(array('template'));
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    public function getTemplateAction($uuid)
    {
        $view = $this->view(
            $this->getDoctrine()->getManager()->getRepository(Template::class)->find($uuid),
            Response::HTTP_OK
        );

        return $this->handleView($view);
    }
}

