<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Converter;

use IDCI\Bundle\DocumentManagementBundle\Converter\HtmlConverter;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * Class HtmlConverterTest
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class HtmlConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConvert
     *
     * @return HtmlConverter
     */
    public function testConvert()
    {
        $html = '<html></html>';

        $htmlConverter = new HtmlConverter();

        $this->assertEquals($html, $htmlConverter->convert($html));

        return $htmlConverter;
    }

    /**
     * testBuildContent
     *
     * @depends testConvert
     */
    public function testBuildContent(HtmlConverter $htmlConverter)
    {
        $template = (new Template())
            ->setHtml('dummy_html')
            ->setCss('dummy_css');

        $expectedContent =<<<EOF
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">dummy_css</style>
    </head>
    <body>dummy_html</body>
</html>
EOF;

        $this->assertEquals(
            $expectedContent,
            $htmlConverter->buildContent($template)
        );
    }

    /**
     * testGetMimeType
     *
     * @param HtmlConverter $htmlConverter
     *
     * @depends testConvert
     */
    public function testGetMimeType(HtmlConverter $htmlConverter)
    {
        $this->assertEquals('text/html', $htmlConverter->getMimeType());
    }
}
