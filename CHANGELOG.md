# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.0.0] - unreleased

First release supporting Guzzle 8.

### Upgrading from guzzle7-adapter

- Require `php-http/guzzle8-adapter` instead of `php-http/guzzle7-adapter`.
- The namespace changed from `Http\Adapter\Guzzle7` to `Http\Adapter\Guzzle8`.
  Both adapters can be installed side by side during a migration.
- PHP 7.4 or newer and `guzzlehttp/guzzle` 8 are required. Read the
  [Guzzle 8 upgrade guide](https://github.com/guzzle/guzzle/blob/8.5/UPGRADING.md)
  for behaviour changes of Guzzle itself, e.g. stricter header and request method handling.
- Exception conversion follows the new Guzzle 8 exception hierarchy:
  - Every `GuzzleHttp\Exception\NetworkException` (`ConnectException`, `ConnectTimeoutException`
    and the new `NetworkTimeoutException`) is converted to `Http\Client\Exception\NetworkException`.
  - Every `GuzzleHttp\Exception\ResponseException` (`ClientException`, `ServerException`,
    `TooManyRedirectsException` and the new `ResponseTransferException` / `ResponseTimeoutException`)
    is converted to `Http\Client\Exception\HttpException` containing the response.
  - Other `GuzzleHttp\Exception\RequestException` are converted to `Http\Client\Exception\RequestException`.
