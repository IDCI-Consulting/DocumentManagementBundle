<?php

/**
 * @license MIT
 */
namespace IDCI\Bundle\DocumentManagementBundle\Generator

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Twig_Environment;
use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterRegistryInterface;

/**
 * Class Generator.
 */
class Generator implements GeneratorInterface
{
    /** @var ConverterRegistryInterface */
    private $converterRegistry;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * Constructor.
     *
     * @param ConverterRegistryInterface $converterRegistry
     * @param \Twig_Environment          $twig
     */
    public function __construct(
        ConverterRegistryInterface $converterRegistry,
        \Twig_Environment          $twig
    ) {
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

        $template = $this->templateRepository->findOne(array('id' => $parameters['template_id']));

        if (!$template) {
            throw new \UnexpectedValueException(sprintf(
                "UnexpectedValueException - Template id: %s doesn't exist.",
                $parameters['template_id']
            ));
        }

        // TODO: Build Data according to TemplateData, given parameters data and reference.


        $html = $this->render(
            $template,
            $parameters['data'],
            $parameters['options']['format']
        );

        if (!$this->converterRegistry->hasConverter($format)) {
            throw new \UnexpectedValueException(sprintf(
                "UnexpectedValueException - Format: %s doesn't exist",
                $format
            ));
        }

        return $this->converterRegistry->getConverter($format)->convert($html);
    }

    /**
     * Returns the rendered Html using twig render to use data and twig template
     *
     * @param Template $template The template.
     * @param array    $data     The fetched data.
     * @param string   $format   The format.
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
            $content = $this->twig->render($content, $data);
        } catch (\Twig_Error_Runtime $e) {
            throw new \Exception(sprintf(
                "%s - Render: %s, this exception was raised may be you use a merge tag not defined in the template: %s",
                get_class($e),
                $e->getMessage(),
                $template->getId()
            ));
        } catch (\Twig_Error $e) {
            throw new \Exception(sprintf(
                "%s - Render: %s, template id: %s",
                get_class($e),
                $e->getMessage(),
                $template->getId()
            ));
        }

        /**
         * Clear twig cache files
         *
         * Twig_Loader_String

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
                'data'       => array(),
                'options'    => array('format' => 'pdf'),
            ))
            ->setAllowedTypes('template_id', array('int', 'string'))
            ->setAllowedTypes('data',        array('string', 'array'))
            ->setAllowedTypes('options',     array('string', 'array'))
            ->setNormalizer('data', function (Options $options, $value) {
                if (is_string($value)) {
                    $value = json_decode($value, true);
                }

                return $value;
            })
            ->setNormalizer('options', function (Options $options, $value) {
                if (is_string($value)) {
                    $value = json_decode($value, true);
                }

                return $value;
            });
    }
}
