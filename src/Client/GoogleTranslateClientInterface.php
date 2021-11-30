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

interface GoogleTranslateClientInterface
{
    /**
     * Set source language for translation.
     *
     * @param string|null $source Language code
     */
    public function setSource(string $source = null): self;

    /**
     * Set target language for translation.
     *
     * @param string $target Language code
     */
    public function setTarget(string $target): self;

    /**
     * Translate text.
     *
     * This can be called from instance method translate() using __call() magic method.
     * Use $instance->translate($string) instead.
     *
     * @param string $string String to translate
     *
     * @throws \ErrorException           If the HTTP request fails
     * @throws \UnexpectedValueException If received data cannot be decoded
     */
    public function translate(string $string): ?string;
}
