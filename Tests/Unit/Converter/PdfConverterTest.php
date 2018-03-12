<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Converter;

use Knp\Snappy\GeneratorInterface;
use IDCI\Bundle\DocumentManagementBundle\Converter\PdfConverter;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * Class PdfConverterTest
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class PdfConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConvert
     *
     * @return PdfConverter
     */
    public function testConvert()
    {
        $html = '<html></html>';

        $wkhtmltopdf = $this->getMockBuilder(GeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $wkhtmltopdf
            ->expects($this->once())
            ->method('getOutputFromHtml')
            ->will($this->returnValue($html));

        $pdfConverter = new PdfConverter($wkhtmltopdf);

        $this->assertEquals($html, $pdfConverter->convert($html));

        return $pdfConverter;
    }

    /**
     * testBuildContent
     *
     * @depends testConvert
     */
    public function testBuildContent(PdfConverter $pdfConverter)
    {
        $template = (new Template())
            ->setHtml('dummy_html')
            ->setCss('dummy_css');

        $expectedContent =<<<EOF
<html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <style type=\"text/css\">dummy_css</style>
    </head>
    <body>dummy_html</body>
</html>
EOF;

        $this->assertEquals(
            $expectedContent,
            $pdfConverter->buildContent($template)
        );
    }

    /**
     * testGetMimeType
     *
     * @param PdfConverter $pdfConverter
     *
     * @depends testConvert
     */
    public function testGetMimeType(PdfConverter $pdfConverter)
    {
        $this->assertEquals('application/pdf', $pdfConverter->getMimeType());
    }
}
