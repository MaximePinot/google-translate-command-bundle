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

namespace MaximePinot\GoogleTranslateCommandBundle\Dumper;

use MaximePinot\GoogleTranslateCommandBundle\Exception\FileDumperNotFoundException;
use Symfony\Component\Translation\Dumper\CsvFileDumper;
use Symfony\Component\Translation\Dumper\FileDumper;
use Symfony\Component\Translation\Dumper\IcuResFileDumper;
use Symfony\Component\Translation\Dumper\IniFileDumper;
use Symfony\Component\Translation\Dumper\JsonFileDumper;
use Symfony\Component\Translation\Dumper\MoFileDumper;
use Symfony\Component\Translation\Dumper\PhpFileDumper;
use Symfony\Component\Translation\Dumper\PoFileDumper;
use Symfony\Component\Translation\Dumper\QtFileDumper;
use Symfony\Component\Translation\Dumper\XliffFileDumper;
use Symfony\Component\Translation\Dumper\YamlFileDumper;

/**
 * @author Maxime Pinot <contact@maximepinot.com>
 */
final class FileDumperFactory
{
    /**
     * Instantiates the right FileDumper for the given extension.
     *
     * @param string $ext A file extension
     *
     * @return FileDumper
     *
     * @throws FileDumperNotFoundException if no suitable FileDumper found
     */
    public static function createFromExtension(string $ext): FileDumper
    {
        switch ($ext)
        {
            case 'csv':
                return new CsvFileDumper();
            case 'ini':
                return new IniFileDumper();
            case 'json':
                return new JsonFileDumper();
            case 'mo':
                return new MoFileDumper();
            case 'php':
                return new PhpFileDumper();
            case 'po':
                return new PoFileDumper();
            case 'res':
                return new IcuResFileDumper();
            case 'ts':
                return new QtFileDumper();
            case 'xlf':
                return new XliffFileDumper();
            case 'yml':
            case 'yaml':
                return new YamlFileDumper($ext);
            default:
                throw FileDumperNotFoundException::fromExtension($ext);
        }
    }
}
