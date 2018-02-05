<?php

/**
 * @license MIT
 */

namespace Tms\Bundle\DocumentBundle\Converter;

use Tms\Bundle\DocumentBundle\Entity\Template;

/**
 * ConverterInterface
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
