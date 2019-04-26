# Google Translate Command Bundle

Automatically translate your Symfony translation files with the Google Translate API.

## Installation

```
$ composer require maximepinot/google-translate-command-bundle --dev
```

## Usage

```
$ php bin/console translation:google-translate <locales>
```

For example, the following command will translate your translation files
into French and Spanish:
```
$ php bin/console translation:google-translate fr es
```

## Documentation

For more information (installation, usage, examples, troubleshooting), [see the documentation](docs/index.md).

## Built With

* [Google Translate PHP](https://github.com/Stichoza/google-translate-php) - Free Google Translate API PHP Package.

## Author

* **Maxime Pinot** - [GitHub](https://github.com/MaximePinot/) | [Website](https://www.maximepinot.com/) | [Twitter](https://twitter.com/MaximePinot) | [LinkedIn](https://www.linkedin.com/in/pinotmaxime/)


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
