<?php

namespace IDCI\Bundle\DocumentManagementBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\DBAL\Types\ConversionException;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializationContext;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use IDCI\Bundle\DocumentManagementBundle\Form\TemplateType;

/**
 * TemplateController
 *
 * @Route(name="api_templates_")
 */
class TemplateController extends FOSRestController
{
    /**
     * [GET] /api/templates
     * Retrieve a set of templates.
     *
     * @return Response
     */
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

    /**
     * [GET] /api/templates/{uuid}
     * Retrieve a template.
     *
     * @param string $uuid
     *
     * @return Response
     */
    public function getTemplateAction($uuid)
    {
        try {
            $template = $this->getDoctrine()->getManager()->getRepository(Template::class)->findByIdOrSlug($uuid);

            if (null === $template) {
                throw new NotFoundHttpException(sprintf(
                    'Template with slug %s not found',
                    $id
                ));
            }

            $view = $this->view(
                $template,
                Response::HTTP_OK
            );

            $context = SerializationContext::create()->setGroups(array('template'));
            $view->setSerializationContext($context);

            return $this->handleView($view);
        } catch (\Exception $e) {
            return $this->handleView($this->view(
                array(),
                Response::HTTP_NOT_FOUND
            ));
        }
    }

    /**
     * [DELETE] /api/templates/{uuid}
     * Delete a template.
     *
     * @param string $uuid
     *
     * @return Response
     */
    public function deleteTemplateAction($uuid)
    {
        $manager = $this->getDoctrine()->getManager();

        $template = $manager->getRepository(Template::class)->find($uuid);

        $manager->remove($template);
        $manager->flush();

        $view = $this->view(
            array(),
            Response::HTTP_NO_CONTENT
        );

        return $this->handleView($view);
    }
}

