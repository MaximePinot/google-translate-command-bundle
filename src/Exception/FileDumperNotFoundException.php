<?php

/*
 * This file is part of the GoogleTranslateCommandBundle.
 *
 * (c) Maxime Pinot <contact@maximepinot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MaximePinot\GoogleTranslateCommandBundle\Exception;

/**
 * @author Maxime Pinot <contact@maximepinot.com>
 */
class FileDumperNotFoundException extends \Exception
{
    public static function fromExtension(string $ext): self
    {
        return new self(sprintf('Could not find a FileDumper that support the "%s" extension.', $ext));
    }
}
