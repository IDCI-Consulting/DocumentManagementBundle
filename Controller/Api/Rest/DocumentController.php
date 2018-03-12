<?php

namespace IDCI\Bundle\DocumentManagementBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\DBAL\Types\ConversionException;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializationContext;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;
use IDCI\Bundle\DocumentManagementBundle\Form\ApiDocumentType;

/**
 * DocumentController
 */
class DocumentController extends FOSRestController
{
    /**
     * [GET] /api/documents
     * Retrieve a set of documents.
     *
     * @QueryParam(name="reference", nullable=true, description="(optional) Reference")
     *
     * @param string reference
     *
     * @return Response
     */
    public function getDocumentsAction($reference = null)
    {
        $view = $this->view(
            $this->getDoctrine()->getManager()->getRepository(Document::class)->findAll(),
            Response::HTTP_OK
        );

        $context = SerializationContext::create()->setGroups(array('document'));
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * [GET] /api/documents/{uuid}
     * Retrieve a document.
     *
     * @param string $uuid
     *
     * @return Response
     */
    public function getDocumentAction($uuid)
    {
        try {
            $view = $this->view(
                $this->getDoctrine()->getManager()->getRepository(Document::class)->find($uuid),
                Response::HTTP_OK
            );

            $context = SerializationContext::create()->setGroups(array('document'));
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
     * [POST] /api/documents
     * Add a document.
     *
     * @RequestParam(name="name", strict=true, nullable=false)
     * @RequestParam(name="reference", strict=true, nullable=false)
     * @RequestParam(name="description", strict=true, nullable=true)
     * @RequestParam(name="data", strict=true, nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */
    public function postDocumentAction(ParamFetcher $paramFetcher)
    {
        $manager = $this->getDoctrine()->getManager();
        $document = new Document();
        $form = $this->createForm(ApiDocumentType::class, $document);
        $view = $this->view(array());

        $form->submit($paramFetcher->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($document);
            $manager->flush();

            $view->setStatusCode(Response::HTTP_CREATED);

            return $this->handleView($view);
        }
    }

    /**
     * [DELETE] /api/documents/{uuid}
     * Delete a document.
     *
     * @param string $uuid
     *
     * @return Response
     */
    public function deleteDocumentAction($uuid)
    {
        $manager = $this->getDoctrine()->getManager();
        $document = $manager->getRepository(Document::class)->find($uuid);
        $view = $this->view(array());

        if (!$document) {
            $view->setStatusCode(Response::HTTP_NOT_FOUND);

            return $this->handleView($view);
        }

        $manager->remove($document);
        $manager->flush();

        $view->setStatusCode(Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}

