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

namespace MaximePinot\GoogleTranslateCommandBundle\Tests\Dumper;

use MaximePinot\GoogleTranslateCommandBundle\Dumper\FileDumperFactory;
use MaximePinot\GoogleTranslateCommandBundle\Exception\FileDumperNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Tests FileDumperFactory class.
 *
 * @author Maxime Pinot <contact@maximepinot.com>
 */
class FileDumperFactoryTest extends TestCase
{
    /**
     * Tests that an Exception is thrown when a FileDumper cannot be found.
     *
     * @throws FileDumperNotFoundException
     */
    public function testExceptionIsThrown(): void
    {
        static::expectException(FileDumperNotFoundException::class);
        static::expectExceptionMessage('Could not find a FileDumper that support the "dummy" extension.');
        FileDumperFactory::createFromExtension('dummy');
    }
}
