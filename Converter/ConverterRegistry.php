<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\DocumentManagementBundle\Converter;

use IDCI\Bundle\DocumentManagementBundle\Exception\UnexpectedTypeException;

/**
 * Class ConverterRegistry.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class ConverterRegistry implements ConverterRegistryInterface
{
    /**
     * @var array
     */
    private $converters;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->converters = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setConverter($alias, ConverterInterface $converter)
    {
        $this->converters[$alias] = $converter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConverter($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!$this->hasConverter($alias)) {
            throw new \InvalidArgumentException(sprintf(
                'InvalidArgumentException - Could not load converter "%s"',
                $alias
            ));
        }

        return $this->converters[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasConverter($alias)
    {
        return isset($this->converters[$alias]);
    }
}
