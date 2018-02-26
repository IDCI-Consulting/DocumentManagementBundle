<?php

namespace IDCI\Bundle\DocumentManagementBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\DBAL\Types\ConversionException;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
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
        try {
            $view = $this->view(
                $this->getDoctrine()->getManager()->getRepository(Template::class)->find($uuid),
                Response::HTTP_OK
            );

            return $this->handleView($view);
        } catch (\Exception $e) {
            return $this->handleView($this->view(
                array(),
                Response::HTTP_NOT_FOUND
            ));
        }
    }

    /**
     * @RequestParam(name="name", strict=true, nullable=false)
     * @RequestParam(name="description", strict=true, nullable=true)
     * @RequestParam(name="html", strict=true, nullable=true)
     * @RequestParam(name="css", strict=true, nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */
    public function postTemplatesAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $template = new Template();

        foreach ($paramFetcher->all() as $name => $value) {
            call_user_func(array($template, sprintf('set%s', ucfirst($name))), $value);
        }

        $em->persist($template);
        $em->flush();

        return $this->handleView($this->view(
            array(),
            Response::HTTP_CREATED
        ));
    }
}

