<?php

/**
 * @license MIT
 */
namespace IDCI\Bundle\DocumentManagementBundle\Generator;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Twig_Environment;
use Twig\Error\RuntimeError;
use Twig\Error\Error;
use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterRegistryInterface;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use IDCI\Bundle\DocumentManagementBundle\Repository\TemplateRepository;

/**
 * Class Generator.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class Generator implements GeneratorInterface
{
    /** @var TemplateRepository */
    private $templateRepository;

    /** @var ConverterRegistryInterface */
    private $converterRegistry;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * Constructor.
     *
     * @param TemplateRepository         $templateRepository
     * @param ConverterRegistryInterface $converterRegistry
     * @param \Twig_Environment          $twig
     */
    public function __construct(
        TemplateRepository         $templateRepository,
        ConverterRegistryInterface $converterRegistry,
        \Twig_Environment          $twig
    ) {
        $this->templateRepository = $templateRepository;
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

        $template = $this->templateRepository->findOne(array('id' => $parameters['template_id']));

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
            $twigTemplate = $this->twig->createTemplate($content);

            $content = $twigTemplate->render(array(
                'data' => $data,
            ));
        } catch (RuntimeError $e) {
            throw new \Exception(sprintf(
                "%s - Render: %s, this exception was raised may be you use not defined data in the template: %s",
                get_class($e),
                $e->getMessage(),
                $template->getId()
            ));
        } catch (Error $e) {
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
