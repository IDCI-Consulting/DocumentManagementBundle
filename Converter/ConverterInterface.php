<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Converter;

use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * ConverterInterface
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
interface ConverterInterface
{
    /**
     * Convert content page to document.
     *
     * @param string $content
     *
     * @return string
     */
    public function convert($content);

    /**
     * Get the MimeType.
     *
     * @return string
     */
    public function getMimeType();

    /**
     * Build document content with a given template.
     *
     * @param Template $template
     *
     * @return string
     */
    public function buildContent(Template $template);
}
