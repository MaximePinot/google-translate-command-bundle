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

namespace MaximePinot\GoogleTranslateCommandBundle\Tests\Loader;

use MaximePinot\GoogleTranslateCommandBundle\Exception\FileLoaderNotFoundException;
use MaximePinot\GoogleTranslateCommandBundle\Loader\FileLoaderFactory;
use PHPUnit\Framework\TestCase;

/**
 * Tests FileLoaderFactory class.
 *
 * @author Maxime Pinot <contact@maximepinot.com>
 */
class FileLoaderFactoryTest extends TestCase
{
    /**
     * Tests that an Exception is thrown when a FileLoader cannot be found.
     *
     * @throws FileLoaderNotFoundException
     */
    public function testException(): void
    {
        static::expectException(FileLoaderNotFoundException::class);
        static::expectExceptionMessage('Could not find a FileLoader that support the "dummy" extension.');
        FileLoaderFactory::createFromExtension('dummy');
    }
}
