<?php

namespace IDCI\Bundle\DocumentManagementBundle\MimeType;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser as BaseMimeTypeExtensionGuesser;

/**
 * MimeTypeExtensionGuesser.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class MimeTypeExtensionGuesser extends BaseMimeTypeExtensionGuesser
{
    /**
     * Guess MimeType
     *
     * @param string $extension
     *
     * @return string
     */
    public function guessMimeType($extension)
    {
        $extensions = array_flip($this->defaultExtensions);

        return isset($extensions[$extension]) ? $extensions[$extension] : null;
    }
}
