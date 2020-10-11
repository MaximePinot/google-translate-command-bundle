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

namespace MaximePinot\GoogleTranslateCommandBundle\Loader;

use MaximePinot\GoogleTranslateCommandBundle\Exception\FileLoaderNotFoundException;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\IcuResFileLoader;
use Symfony\Component\Translation\Loader\IniFileLoader;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\MoFileLoader;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Loader\QtFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;

/**
 * @author Maxime Pinot <contact@maximepinot.com>
 */
class FileLoaderFactory
{
    /**
     * Instantiates the right FileLoader for the given extension.
     *
     * @param string $ext A file extension
     *
     * @throws FileLoaderNotFoundException if no suitable FileLoader found
     */
    public static function createFromExtension(string $ext): LoaderInterface
    {
        switch ($ext)
        {
            case 'csv':
                return new CsvFileLoader();
            case 'res':
                return new IcuResFileLoader();
            case 'ini':
                return new IniFileLoader();
            case 'json':
                return new JsonFileLoader();
            case 'mo':
                return new MoFileLoader();
            case 'php':
                return new PhpFileLoader();
            case 'po':
                return new PoFileLoader();
            case 'ts':
                return new QtFileLoader();
            case 'xlf':
                return new XliffFileLoader();
            case 'yml':
            case 'yaml':
                return new YamlFileLoader();
            default:
                throw FileLoaderNotFoundException::fromExtension($ext);
        }
    }
}
