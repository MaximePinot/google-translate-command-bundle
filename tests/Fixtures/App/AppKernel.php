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

namespace MaximePinot\GoogleTranslateCommandBundle\Tests\Fixtures\App;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \MaximePinot\GoogleTranslateCommandBundle\GoogleTranslateCommandBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yaml');
        $loader->load(__DIR__ . '/config/services_' . $this->getEnvironment() . '.yaml');
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/var/cache';
    }

    public function getLogDir(): string
    {
        return __DIR__ . '/var/log';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
