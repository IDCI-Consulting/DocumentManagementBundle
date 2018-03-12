<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Converter;

use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterRegistry;
use IDCI\Bundle\DocumentManagementBundle\Converter\ConverterInterface;
use IDCI\Bundle\DocumentManagementBundle\Converter\PdfConverter;

/**
 * Class ConverterRegistryTest
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class ConverterRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testSetConverter
     *
     * @return ConverterRegistry
     */
    public function testSetConverter()
    {
        $pdfConverter =
            $this->getMockBuilder(PdfConverter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $registry = new ConverterRegistry();

        $this->assertInstanceOf(
            ConverterRegistry::class,
            $registry->setConverter('pdf', $pdfConverter)
        );

        return $registry;
    }

    /**
     * testHasConverter
     *
     * @param ConverterRegistry $registry
     *
     * @depends testSetConverter
     */
    public function testHasConverter(ConverterRegistry $registry)
    {
        $this->assertTrue($registry->hasConverter('pdf'));
        $this->assertFalse($registry->hasConverter('docx'));
    }

    /**
     * testGetConverter
     *
     * @param ConverterRegistry $registry
     *
     * @depends testSetConverter
     */
    public function testGetConverter(ConverterRegistry $registry)
    {
        $this->assertInstanceOf(
            ConverterInterface::class,
            $registry->getConverter('pdf')
        );
    }

    /**
     * @param ConverterRegistry $registry
     *
     * @expectedException IDCI\Bundle\DocumentManagementBundle\Exception\UnexpectedTypeException
     *
     * @depends testSetConverter
     */
    public function testUnexpectedTypeException(ConverterRegistry $registry)
    {
        $registry->getConverter(array());
    }

    /**
     * @param ConverterRegistry $registry
     *
     * @expectedException \InvalidArgumentException
     *
     * @depends testSetConverter
     */
    public function testInvalidArgumentException(ConverterRegistry $registry)
    {
        $registry->getConverter('docx');
    }
}
