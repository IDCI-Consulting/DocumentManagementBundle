<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Converter;

use Knp\Snappy\GeneratorInterface;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * Class PdfConverter.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class PdfConverter implements ConverterInterface
{
    /**
     * @var GeneratorInterface
     */
    protected $wkhtmltopdf;

    /**
     * Constructor.
     *
     * @param GeneratorInterface $wkhtmltopdf
     */
    public function __construct(GeneratorInterface $wkhtmltopdf)
    {
        $this->wkhtmltopdf = $wkhtmltopdf;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($content)
    {
        return $this->wkhtmltopdf->getOutputFromHtml($content);
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        return 'application/pdf';
    }

    /**
     * {@inheritdoc}
     */
    public function buildContent(Template $template)
    {
        $content = <<<EOF
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">%s</style>
    </head>
    <body>%s</body>
</html>
EOF;

        return sprintf(
            $content,
            $template->getCss(),
            $template->getHtml()
        );
    }
}
