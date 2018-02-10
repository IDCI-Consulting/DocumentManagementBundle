<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Generator;

use \Twig_Environment;
use \Twig_Error;
use \Twig_Error_Runtime;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use IDCI\Bundle\DocumentManagementBundle\Exception\MissingGenerationParametersException;
use IDCI\Bundle\DocumentManagementBundle\Repository\TemplateRepository;
use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterRegistryInterface;
use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterInterface;
use IDCI\Bundle\DocumentManagementBundle\Converter\PdfConverter;
use IDCI\Bundle\DocumentManagementBundle\Generator\Generator;

/**
 * Class GeneratorTest
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Template
     */
    protected $template;

    /**
     * @var TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var ConverterRegistryInterface
     */
    protected $converterRegistry;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->templateRepository = $this->getMockBuilder(TemplateRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array('findOne'))
            ->getMock();

        $this->converterRegistry = $this->getMockBuilder(ConverterRegistryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twig = $this->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->generator = new Generator($this->templateRepository, $this->converterRegistry, $this->twig);
    }


    /**
     * generateConfig
     */
    public function generateConfig()
    {
        $this->template = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->getMock();

        $converter = $this->getMockBuilder(ConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue(
                $this->template
            ));

        $this->converterRegistry
            ->expects($this->once())
            ->method('hasConverter')
            ->will($this->returnValue(true));

        $this->converterRegistry
            ->expects($this->any())
            ->method('getConverter')
            ->will($this->returnValue($converter));

        $converter
            ->expects($this->once())
            ->method('buildContent')
            ->will($this->returnValue('dummy'));
    }

    /**
     * testGenerate
     */
    public function testGenerate()
    {
        $this->generateConfig();

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array('format' => 'html'),
        );

        $this->assertNull($this->generator->generate($parameters));
    }

    /**
     * testGenerate
     */
    public function testPreview()
    {
        $this->generateConfig();

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array('format' => 'html'),
        );

        $this->assertNull($this->generator->generate($parameters));
    }
    /**
     * @expectedException \RuntimeException
     */
    public function testRuntimeException()
    {
        $this->generateConfig();

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array('format' => 'html'),
        );

        $this->assertNull($this->generator->generate($parameters));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testUnexpectedValueExceptionWhenNoTemplate()
    {
        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue(false));

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array(),
        );

        $this->generator->generate($parameters);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testUnexpectedValueExceptionWhenNoConverter()
    {
        $converter = $this->getMockBuilder(ConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue(new Template()));

        $this->converterRegistry
            ->expects($this->once())
            ->method('hasConverter')
            ->will($this->returnValue(false));

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array('format' => 'html'),
        );

        $this->generator->generate($parameters);
    }

    /**
     * @expectedException \Exception
     */
    public function testTwigErrorRuntime()
    {
        $converter = $this->getMockBuilder(ConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $converter
            ->expects($this->once())
            ->method('buildContent')
            ->will($this->returnValue('dummy_content'));

        $this->converterRegistry
            ->expects($this->any())
            ->method('hasConverter')
            ->will($this->returnValue(true));

        $this->converterRegistry
            ->expects($this->any())
            ->method('getConverter')
            ->will($this->returnValue($converter));

        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue(new Template()));

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->will($this->throwException(new Twig_Error_Runtime('ERROR')));

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array('format' => 'html'),
        );

        $this->generator->generate($parameters);
    }

    /**
     * @expectedException \Exception
     */
    public function testTwigError()
    {
        $converter = $this->getMockBuilder(ConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->converterRegistry
            ->expects($this->any())
            ->method('hasConverter')
            ->will($this->returnValue(true));

        $this->converterRegistry
            ->expects($this->any())
            ->method('getConverter')
            ->will($this->returnValue($converter));

        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue(new Template()));

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->will($this->throwException(new \Twig_Error('ERROR')));

        $parameters = array(
            'template_id' => '0',
            'data' => array(),
            'options' => array('format' => 'html'),
        );

        $this->generator->generate($parameters);
    }
}
