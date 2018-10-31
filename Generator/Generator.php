<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Generator;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Twig\Error\RuntimeError;
use Twig\Error\Error;
use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterRegistryInterface;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;

/**
 * Class Generator.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class Generator implements GeneratorInterface
{
    /** @var ObjectManager */
    private $manager;

    /** @var ConverterRegistryInterface */
    private $converterRegistry;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * Constructor.
     *
     * @param ObjectManager              $manager,
     * @param ConverterRegistryInterface $converterRegistry
     * @param \Twig_Environment          $twig
     */
    public function __construct(
        ObjectManager              $manager,
        ConverterRegistryInterface $converterRegistry,
        \Twig_Environment          $twig
    ) {
        $this->manager = $manager;
        $this->converterRegistry = $converterRegistry;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(array $parameters = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $parameters = $resolver->resolve($parameters);
        $format = $parameters['options']['format'];

        $template = $this->manager
            ->getRepository(Template::class)
            ->find($parameters['template_id']);

        if (!$template) {
            throw new \UnexpectedValueException(sprintf(
                "UnexpectedValueException - Template id: %s doesn't exist.",
                $parameters['template_id']
            ));
        }

        if (!$this->converterRegistry->hasConverter($format)) {
            throw new \UnexpectedValueException(sprintf(
                "UnexpectedValueException - Format: %s doesn't exist",
                $format
            ));
        }

        $html = $this->render(
            $template,
            $parameters['data'],
            $format
        );

        return $this->converterRegistry->getConverter($format)->convert($html);
    }

    /**
     * Generate a document.
     *
     * @param Document $document
     *
     * @return string
     */
    public function generateDocument(Document $document)
    {
        $parameters = array(
            'template_id' => $document->getTemplate()->getId(),
            'data' => $document->getData(),
            'options' => array('format' => $document->getFormat()),
        );

        return $this->generate($parameters);
    }

    /**
     * Generate a document from a template with given data and options.
     *
     * @param Template $template the template
     * @param mixed    $data     the data to merge could be a json or and array
     * @param mixed    $options  the options could be a json or and array
     *
     * @return string
     */
    public function generateDocumentFromTemplate(Template $template, $data, $options)
    {
        $parameters = array(
            'template_id' => $template->getId(),
            'data' => $data,
            'options' => $options,
        );

        return $this->generate($parameters);
    }

    /**
     * Returns the rendered Html using twig render to use data and twig template.
     *
     * @param Template $template the template
     * @param array    $data     the fetched data
     * @param string   $format   the format
     *
     * @return string
     *
     * @throws \Exception Previous: Twig_Error_Loader, Twig_Error_Syntax, Twig_Error_Runtime.
     *                    Twig render error.
     */
    private function render(Template $template, array $data, $format)
    {
        $converter = $this->converterRegistry->getConverter($format);
        $content = $converter->buildContent($template);

        try {
            $twigTemplate = $this->twig->createTemplate($content);

            $content = $twigTemplate->render(array(
                'data' => $data,
            ));
        } catch (RuntimeError $e) {
            throw new \Exception(sprintf(
                '%s - Render: %s, this exception was raised may be you use not defined data in the template: %s',
                get_class($e),
                $e->getMessage(),
                $template->getId()
            ));
        } catch (Error $e) {
            throw new \Exception(sprintf(
                '%s - Render: %s, template id: %s',
                get_class($e),
                $e->getMessage(),
                $template->getId()
            ));
        }

        /*
         * Clear twig cache files
         *
         * When using this loader with a cache mechanism, you should know that a new cache
         * key is generated each time a template content "changes" (the cache key being the
         * source code of the template). If you don't want to see your cache grows out of
         * control, you need to take care of clearing the old cache file by yourself.
         */
        $this->twig->clearCacheFiles();

        return $content;
    }

    /**
     * Configure generator options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array('template_id'))
            ->setDefaults(array(
                'data' => array(),
                'options' => array('format' => 'pdf'),
            ))
            ->setAllowedTypes('template_id', array('int', 'string'))
            ->setAllowedTypes('data', array('array'))
            ->setAllowedTypes('options', array('array'));
    }
}
