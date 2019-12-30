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

namespace MaximePinot\GoogleTranslateCommandBundle\Tests\Command;

use MaximePinot\GoogleTranslateCommandBundle\Command\GoogleTranslateCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * Tests the 'translation:google-translate' command.
 *
 * @author Maxime Pinot <contact@maximepinot.com>
 */
class GoogleTranslateCommandTest extends KernelTestCase
{
    /**
     * @var GoogleTranslateCommand
     */
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        static::bootKernel();
        $application = new Application(static::$kernel);
        $this->command = $application->find('translation:google-translate');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->command = null;

        // Remove generated translations
        (new Filesystem())->remove(static::$kernel->getProjectDir() . '/translations/google_translated');
    }

    /**
     * Tests that the command successfully create and translate files.
     *
     * @param string $ext
     *
     * @dataProvider extensionsProvider
     */
    public function testCommand(string $ext): void
    {
        $locales = ['fr', 'es'];
        $outputDir = '/translations/google_translated/' . $ext;
        $translationDir = '/translations/' . $ext;

        $commandTester = new CommandTester($this->command);

        $input = [
            $this->command->getName(),
            'locales' => $locales,
            '--translations-dir' => $translationDir,
            '--output' => $outputDir,
        ];

        $commandTester->execute($input);

        $domainSuffix = class_exists(\MessageFormatter::class) ? MessageCatalogueInterface::INTL_DOMAIN_SUFFIX : '';

        foreach ($locales as $locale)
        {
            $format = static::$kernel->getProjectDir() . $outputDir . '/%s.' . $locale . '.' . $ext;
            $messageDomainFile = sprintf($format, 'messages' . $domainSuffix);
            $formsDomainFile = sprintf($format, 'forms' . $domainSuffix);

            // Assert files were created for each domain
            static::assertFileExists($formsDomainFile);
            static::assertFileExists($messageDomainFile);

            $format = static::$kernel->getProjectDir() . $translationDir . '/expected/%s.' . $locale . '.' . $ext;

            // Assert files were translated
            static::assertFileEqualsIgnoreEOL($messageDomainFile, sprintf($format, 'messages'));
            static::assertFileEqualsIgnoreEOL($formsDomainFile, sprintf($format, 'forms'));
        }
    }

    /**
     * Provdes a list of extensions that can be tested as translation files
     * were created in Fixtures/App/translations for test purposes.
     *
     * @return array
     */
    public function extensionsProvider(): array
    {
        return [
            ['csv'],
            // ['xlf'], Removed on purpose as unique ids are generated. Tested in its own function: testXlf().
            ['yaml'],
        ];
    }

    /**
     * Tests that XLIFF translations files are created and translated.
     */
    public function testXlf(): void
    {
        $locales = ['fr', 'es'];
        $outputDir = '/translations/google_translated/xlf';

        $commandTester = new CommandTester($this->command);
        $input = [
            $this->command->getName(),
            'locales' => $locales,
            '--translations-dir' => '/translations/xlf',
            '--output' => $outputDir,
        ];
        $commandTester->execute($input);

        foreach ($locales as $locale)
        {
            $format = static::$kernel->getProjectDir() . $outputDir . '/%s.' . $locale . '.xlf';
            $messageDomainFile = sprintf($format, 'messages');
            $formsDomainFile = sprintf($format, 'forms');

            // Assert files were created for each domain
            static::assertFileExists($messageDomainFile);
            static::assertFileExists($formsDomainFile);

            $messageDomainContent = file_get_contents($messageDomainFile);
            $formsDomainContent = file_get_contents($formsDomainFile);

            // Assert files were translated
            if ('fr' === $locale)
            {
                static::assertContains('<target>Bonjour</target>', $messageDomainContent);
                static::assertContains('<target>Bienvenue</target>', $messageDomainContent);

                static::assertContains('<target>Mot de passe</target>', $formsDomainContent);
                static::assertContains('<target>Soumettre</target>', $formsDomainContent);
            }
            else
            {
                static::assertContains('<target>Hola</target>', $messageDomainContent);
                static::assertContains('<target>Bienvenido</target>', $messageDomainContent);

                static::assertContains('<target>Contraseña</target>', $formsDomainContent);
                static::assertContains('<target>Enviar</target>', $formsDomainContent);
            }
        }
    }

    /**
     * Tests that an entry which was already translated by the command or
     * someone is not translated by the Google Translate API.
     */
    public function testExistingEntriesAreNotTranslated(): void
    {
        $outputDir = '/translations/google_translated/xlf';

        $commandTester = new CommandTester($this->command);
        $input = [
            $this->command->getName(),
            'locales' => ['fr'],
            '--translations-dir' => '/translations/xlf',
            '--output' => $outputDir,
        ];
        $commandTester->execute($input);

        $messageDomainFile = static::$kernel->getProjectDir() . $outputDir . '/messages.fr.xlf';
        static::assertFileExists($messageDomainFile);

        $messageDomainContent = file_get_contents($messageDomainFile);
        $needle = '<target>Cette entrée est déjà traduite en français et ne devrait donc pas être traduite par la commande.</target>';
        static::assertContains($needle, $messageDomainContent);
    }

    /**
     * @param string $expected
     * @param string $actual
     */
    private static function assertFileEqualsIgnoreEOL(string $expected, string $actual): void
    {
        static::assertEquals(file($expected, FILE_IGNORE_NEW_LINES), file($actual, FILE_IGNORE_NEW_LINES));
    }
}
