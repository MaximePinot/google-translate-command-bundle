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

namespace MaximePinot\GoogleTranslateCommandBundle\Client;

use Stichoza\GoogleTranslate\GoogleTranslate;

final class GoogleTranslateClient implements GoogleTranslateClientInterface
{
    private GoogleTranslate $googleTranslate;

    public function __construct()
    {
        $this->googleTranslate = new GoogleTranslate();
    }

    public function setSource(string $source = null): GoogleTranslateClientInterface
    {
        $this->googleTranslate->setSource($source);

        return $this;
    }

    public function setTarget(string $target = null): GoogleTranslateClientInterface
    {
        $this->googleTranslate->setTarget($target);

        return $this;
    }

    public function translate(string $string): ?string
    {
        return $this->googleTranslate->translate($string);
    }
}
