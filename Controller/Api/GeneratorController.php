<?php

namespace IDCI\Bundle\DocumentManagementBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ramsey\Uuid\Uuid;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * GeneratorController.
 */
class GeneratorController extends Controller
{
    /**
     * HTTP Response with header filed Content-Type as The MIME type of the document generated.
     * Allow to show the document generated directly in the browser.
     *
     * @Route("/documents/{id}/generate", name="idci_document_generate_document")
     * @Method({"GET"})
     *
     * @param Request $request data and options
     * @param string  $uuid    the document uuid
     *
     * @return Response
     */
    public function generateDocumentAction(Request $request, Document $document)
    {
        $response = new Response();

        try {
            $content = $this->get('idci_document.generator')->generateDocument($document);
            $converter = $this->get('idci_document.converter.registry')->getConverter($document->getFormat());

            $response->headers->set(
                'Content-Type',
                $converter->getMimeType()
            );

            $response->setStatusCode(200);
            $response->setContent($content);
        } catch (NotFoundHttpException $e) {
            $response->setStatusCode(404);
            $response->setContent($e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $response->setContent($e->getMessage());
        }

        return $response;
    }

    /**
     * HTTP Response with header filed Content-Type as The MIME type of the document generated.
     * Allow to show the document generated from a template directly in the browser.
     *
     * @Route("/templates/{id}/generate", name="idci_document_generate_document_from_template")
     * @Method({"GET"})
     *
     * @param Request $request data and options
     * @param string  $uuid    the template uuid
     *
     * @return Response
     */
    public function generateDocumentFromTemplateAction(Request $request, $id)
    {
        $response = new Response();

        try {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);

            $resolvedOptions = $resolver->resolve(array(
                'data' => $request->query->get('data'),
                'options' => $request->query->get('options'),
            ));

            $template = $this->getDoctrine()->getManager()->getRepository(Template::class)->findByIdOrSlug($id);

            if (null === $template) {
                throw new NotFoundHttpException(sprintf(
                    'Template with slug %s not found',
                    $id
                ));
            }

            $content = $this->get('idci_document.generator')->generateDocumentFromTemplate(
                $template,
                $resolvedOptions['data'],
                $resolvedOptions['options']
            );

            $converter = $this
                ->get('idci_document.converter.registry')
                ->getConverter($resolvedOptions['options']['format']);

            $response->headers->set(
                'Content-Type',
                $converter->getMimeType()
            );

            $response->setStatusCode(200);
            $response->setContent($content);
        } catch (NotFoundHttpException $e) {
            $response->setStatusCode(404);
            $response->setContent($e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $response->setContent($e->getMessage());
        }

        return $response;
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(array('data', 'options'))
            ->setAllowedTypes('data', array('null', 'string', 'array'))
            ->setAllowedTypes('options', array('null', 'string', 'array'))
            ->setNormalizer('data', function (Options $options, $value) {
                if (null === $value) {
                    $value = array();
                }

                if (is_string($value)) {
                    $value = json_decode($value, true);
                }

                return $value;
            })
            ->setNormalizer('options', function (Options $options, $value) {
                if (null === $value) {
                    $value = array('format' => 'pdf');
                }

                if (is_string($value)) {
                    $value = json_decode($value, true);
                }

                return $value;
            });
    }
}
