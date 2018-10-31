<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Generator;

/**
 * Interface GeneratorInterface.
 */
interface GeneratorInterface
{
    /**
     * Generate a document from given parameters.
     *
     * @param array $parameters the parameters to generate the document
     *
     * @return string
     *
     * @throws \UnexpectedValueException When the template or format cannot be found
     */
    public function generate(array $parameters = array());
}
