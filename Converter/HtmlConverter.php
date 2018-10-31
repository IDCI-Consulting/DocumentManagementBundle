<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Converter;

use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * HtmlConverter.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class HtmlConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($content)
    {
        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        return 'text/html';
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
