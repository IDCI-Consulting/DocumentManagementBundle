<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Converter;

use IDCI\Bundle\DocumentManagementBundle\Exception\UnexpectedTypeException;

/**
 * Interface ConverterRegistryInterface
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
interface ConverterRegistryInterface
{
    /**
     * Register the Converter.
     *
     * @param string             $alias     The alias converter.
     * @param ConverterInterface $converter The converter.
     *
     * @return ConverterRegistryInterface
     */
    public function setConverter($alias, ConverterInterface $converter);

    /**
     * Returns the Converter relevant to the alias.
     *
     * @param string $alias
     *
     * @return ConverterInterface
     *
     * @throws UnexpectedTypeException   when the passed alias is not a string.
     * @throws \InvalidArgumentException when the converter can not be retrieved.
     */
    public function getConverter($alias);

    /**
     * Checks the existence of Converter relevant to the alias.
     *
     * @param string $alias check the converter relevant to the alias.
     *
     * @return bool
     */
    public function hasConverter($alias);
}
