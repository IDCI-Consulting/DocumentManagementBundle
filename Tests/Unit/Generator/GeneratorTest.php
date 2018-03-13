<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Template as TwigTemplate;
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
class GeneratorTest extends TestCase
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
     * @var TwigTemplate
     */
    protected $twigTemplate;

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

        $this->twig = new \Twig_Environment(new \Twig_Loader_Array());
        $this->twig->enableStrictVariables();

        $this->template = $this->getMockBuilder(Template::class)
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
            ->will($this->returnValue($this->template));

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
            ->will($this->returnValue('Hello {{ data.firstname }}'));
    }

    /**
     * testGenerate
     */
    public function testGenerate()
    {
        $this->generateConfig();

        $parameters = array(
            'template_id' => '0',
            'data' => array('firstname' => 'foo'),
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
        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue($this->template));

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
            ->will($this->returnValue('Hello {{ data.dummy.foo }}'));

        $this->converterRegistry
            ->expects($this->any())
            ->method('hasConverter')
            ->will($this->returnValue(true));

        $this->template
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('0'));

        $this->converterRegistry
            ->expects($this->any())
            ->method('getConverter')
            ->will($this->returnValue($converter));

        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue($this->template));

        $parameters = array(
            'template_id' => '0',
            'data' => array('firstname' => 'foo'),
            'options' => array('format' => 'pdf'),
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

        $converter
            ->expects($this->once())
            ->method('buildContent')
            ->will($this->returnValue('{{ data.dummy }'));

        $this->converterRegistry
            ->expects($this->any())
            ->method('hasConverter')
            ->will($this->returnValue(true));

        $this->converterRegistry
            ->expects($this->any())
            ->method('getConverter')
            ->will($this->returnValue($converter));

        $this->template
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('0'));

        $this->templateRepository
            ->expects($this->once())
            ->method('findOne')
            ->will($this->returnValue($this->template));

        $parameters = array(
            'template_id' => '0',
        );

        $this->generator->generate($parameters);
    }
}
