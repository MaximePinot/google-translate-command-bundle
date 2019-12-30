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

namespace MaximePinot\GoogleTranslateCommandBundle\Command;

use ErrorException;
use MaximePinot\GoogleTranslateCommandBundle\Dumper\FileDumperFactory;
use MaximePinot\GoogleTranslateCommandBundle\Loader\FileLoaderFactory;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Writer\TranslationWriter;

/**
 * A command that translates Symfony translation files
 * with the Google Translate API.
 *
 * @author Maxime Pinot <contact@maximepinot.com>
 */
class GoogleTranslateCommand extends Command
{
    /*
     * Arguments and options.
     */
    const LOCALES_ARG = 'locales';
    const LOCALE_OPT = 'locale';
    const OUTPUT_DIR_OPT = 'output';
    const TRANSLATIONS_DIR_OPT = 'translations-dir';
    const DELAY_OPT = 'delay';

    /*
     * Default values.
     */
    const DEFAULT_TRANSLATIONS_DIR = '/translations';

    /**
     * @var string The name of the command
     */
    protected static $defaultName = 'translation:google-translate';

    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * GoogleTranslateCommand constructor.
     *
     * @param string $projectDir
     * @param string $defaultLocale
     */
    public function __construct(string $projectDir, string $defaultLocale)
    {
        parent::__construct();

        $this->projectDir = $projectDir;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Translates your translation files with Google Translate')
            ->setHelp('The translation:google-translate command will automatically translate your project translation files using the Google Translate API.');

        $this
            ->addArgument(self::LOCALES_ARG, InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'One or more locales into which you want to translate your translation files.')
            ->addOption(self::LOCALE_OPT, 'l', InputOption::VALUE_OPTIONAL, 'The source locale. If not set, the project default locale is used (defined in "config/packages/framework.yaml").')
            ->addOption(self::OUTPUT_DIR_OPT, 'o', InputOption::VALUE_OPTIONAL, 'The output directory. If not set, translated files are created or merged in the translations directory.')
            ->addOption(self::TRANSLATIONS_DIR_OPT, 'd', InputOption::VALUE_OPTIONAL, 'The directory where to look for translation files. If not set, the command searches in "translations/".')
            ->addOption(self::DELAY_OPT, 's', InputOption::VALUE_OPTIONAL, 'Delay in seconds between each call to the Google Translate API. No delay is set by default. This can lead to a "429 Too Many Requests" error.')
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \MaximePinot\GoogleTranslateCommandBundle\Exception\FileLoaderNotFoundException
     * @throws \MaximePinot\GoogleTranslateCommandBundle\Exception\FileDumperNotFoundException
     * @throws ErrorException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $googleTranslate = new GoogleTranslate();

        $sleepDelay = (int) $input->getOption(self::DELAY_OPT);

        $sourceLocale = $input->getOption(self::LOCALE_OPT) ?? $this->defaultLocale;
        $googleTranslate->setSource($sourceLocale);

        $targetLocales = $input->getArgument(self::LOCALES_ARG);

        $translationsDir = $this->projectDir . ($input->getOption(self::TRANSLATIONS_DIR_OPT) ?? self::DEFAULT_TRANSLATIONS_DIR);
        $translationFiles = (new Finder())->files()->name('*' . $sourceLocale . '*')->in($translationsDir)->depth(0);

        $outputDir = $this->projectDir . ($input->getOption(self::OUTPUT_DIR_OPT) ?? $input->getOption(self::TRANSLATIONS_DIR_OPT));

        /** @var SplFileInfo $translationFile */
        foreach ($translationFiles as $translationFile)
        {
            $ext = $translationFile->getExtension();
            $domain = explode('.', $translationFile->getFilename())[0];
            $fileLoader = FileLoaderFactory::createFromExtension($ext);
            $messages = $fileLoader->load($translationFile->getRealPath(), $sourceLocale, $domain)->all();

            foreach ($targetLocales as $targetLocale)
            {
                $targetFile = sprintf('%s.%s.%s', $domain, $targetLocale, $ext);

                try
                {
                    $messageCatalogue = $fileLoader->load($translationsDir . '/' . $targetFile, $targetLocale, $domain);
                }
                catch (NotFoundResourceException $e)
                {
                    $messageCatalogue = new MessageCatalogue($targetLocale);
                }

                $output->writeln(sprintf('Translating "%s" from "%s" to "%s".', $translationFile->getFilename(), $sourceLocale, $targetLocale));

                $googleTranslate->setTarget($targetLocale);

                $translatedMessages = [];
                foreach ($messages[$domain] as $source => $target)
                {
                    if ($messageCatalogue->has($source, $domain))
                    {
                        if ($output->isDebug())
                        {
                            $output->writeln(sprintf('Skipped "%s".', $source));
                        }

                        continue;
                    }

                    sleep($sleepDelay);
                    $translatedMessages[$source] = $googleTranslate->translate($target) ?? $target;

                    if ($output->isVerbose())
                    {
                        $output->writeln(sprintf('Translated "%s" from "%s" to "%s".', $source, $sourceLocale, $targetLocale));
                    }
                }

                $messageCatalogue->add($translatedMessages, $domain);

                $writer = new TranslationWriter();
                $writer->addDumper($ext, FileDumperFactory::createFromExtension($ext));
                $writer->write($messageCatalogue, $ext, ['path' => $outputDir]);
            }
        }

        return 0;
    }
}
