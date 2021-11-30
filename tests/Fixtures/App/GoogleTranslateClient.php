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

use MaximePinot\GoogleTranslateCommandBundle\Client\GoogleTranslateClientInterface;

final class GoogleTranslateClient implements GoogleTranslateClientInterface
{
    private const TRANSLATIONS = [
        'en' => [
            'Hello' => [
                'fr' => 'Bonjour',
                'es' => 'Hola',
            ],
            'Goodbye' => [
                'fr' => 'Au revoir',
                'es' => 'Adiós',
            ],
            'Username' => [
                'fr' => 'Nom d\'utilisateur',
                'es' => 'Nombre de usuario',
            ],
            'Password' => [
                'fr' => 'Mot de passe',
                'es' => 'Contraseña',
            ],
        ],
    ];

    private ?string $source;
    private ?string $target;

    public function setSource(string $source = null): GoogleTranslateClientInterface
    {
        $this->source = $source;

        return $this;
    }

    public function setTarget(string $target): GoogleTranslateClientInterface
    {
        $this->target = $target;

        return $this;
    }

    public function translate(string $string): ?string
    {
        return self::TRANSLATIONS[$this->source][$string][$this->target] ?? null;
    }
}
