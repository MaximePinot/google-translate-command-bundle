# Troubleshooting

If you run into an issue, feel free to [open a new issue on GitHub](https://github.com/MaximePinot/google-translate-command-bundle/issues).

## cURL error

If you ran into a `cURL error` such as the following:
```
cURL error 77: error setting certificate verify locations: CAfile: C:\xampp\apache\bin\curl-ca-bundle.crt CApath: none (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)
```

It is not an issue related to this Bundle nor its dependencies.
[See this link to find solutions](https://github.com/Stichoza/google-translate-php/issues/105#issuecomment-470434889).

## "429 Too Many Requests" or "503 Service Unavailable"

If you are getting this error, it is most likely that Google has banned your external IP address and/or requires you to solve a CAPTCHA.
To avoid this, use the `--delay` option.

For example:
```
$ php bin/console translation:google-translate fr --delay=1
```

The command will wait 1 second between each call to the Google Translate API.
