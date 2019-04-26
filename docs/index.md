# Google Translate Command Bundle

Automatically translate your Symfony translation files with the Google Translate API.

## Table of Contents

* [Installation](#installation)
* [Usage](#usage)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

## Installation

Open a command console, enter your project directory and execute:

```console
$ composer require maximepinot/google-translate-command-bundle --dev
```

## Usage

```
$ php bin/console translation:google-translate [options] [--] <locales>...
```

Arguments:
  * `locales` One or more locales into which you want to translate your translation files.

Options:
  * `--locale[=LOCALE]`                      The source locale. If not set, the project default locale is used (defined in `config/packages/framework.yaml`).
  * `--output[=OUTPUT]`                      The output directory. If not set, translated files are created or merged in the translations directory.
  * `--translations-dir[=TRANSLATIONS-DIR]`  The directory where to look for translation files. If not set, the command searches in `translations/`.
  * `-delay[=DELAY]`                         Delay in seconds between each call to the Google Translate API. No delay is set by default. [This can lead to a "429 Too Many Requests" error.](troubleshooting.md#429-too-many-requests-or-503-service-unavailable)

## Examples

Here is the structure of a Symfony project:
```
my_project
|_ assets/
|_ bin/
|_ config/
|_ public/
|_ src/
|_ templates/
    |_ messages.en.xlf
    |_ forms.en.xlf
|_ var/
|_ vendor/
```

If you run the following command:

```
$ php bin/console translation:google-translate fr es
```

* The command looks for translation files in the `translations/` directory that contains the project default locale (set in `config/packages/framework.yaml`) in their names.
In the example above, it will find `messages.en.xlf` and `forms.en.xlf`.
* Then, it translates these files in French and Spanish using the Google Translate API.
* The translated files are created in the `translations`.

Output:
```
my_project
|_ assets/
|_ bin/
|_ config/
|_ public/
|_ src/
|_ templates/
    |_ forms.en.xlf
    |_ forms.es.xlf
    |_ forms.fr.xlf
    |_ messages.en.xlf
    |_ messages.es.xlf
    |_ messages.fr.xlf
|_ var/
|_ vendor/
```

**Notes:**
* The command only looks for entries that are not already translated. 
If you run the above command again, nothing will be translated.
New entries are translated and merged with the existing translation files.

* The command can translate any file supported by Symfony (xlf, yaml, json, csv...)

[Click here to see more examples](examples.md).

## Troubleshooting

* [cURL error](troubleshooting.md#curl-error)
* ["429 Too Many Requests" or "503 Service Unavailable"](troubleshooting.md#429-too-many-requests-or-503-service-unavailable)
