# Examples

## Table of Contents

* [Default example (without option)](#default-example-without-option)
* [Using the --locale option](#using-the---locale-option)
* [Using the --output option](#using-the---output-option)
* [Using the --translation-dir option](#using-the---translation-dir-option)

## Default example (without option)

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

## Using the --locale option

```
$ php bin/console translation:google-translate fr es --locale=fr
```

The command will look for files containing `fr` in their names instead of your project default locale.

## Using the --output option

```
$ php bin/console translation:google-translate fr es --output=translations/google_translated
```

The command will create the translated files in the `translations/google_translated/` directory instead of the `translations/` directory.

## Using the --translation-dir option

This option is useful if your Symfony project does not follow the usual Symfony structure.

```
$ php bin/console translation:google-translate fr es --translations-dir=/app/translations
```

The command will look for translation files in `app/translations` directory instead of the `translations/` directory.

## Using the --delay option

```
$ php bin/console translation:google-translate fr es --delay=1
```

The command will wait 1 second between each call to the Google Translate API. 

If you have a lot of entries to translate, you should set this option
to avoid a ["429 Too Many Requests" or "503 Service Unavailable" error](troubleshooting.md#429-too-many-requests-or-503-service-unavailable).
