# Homepage Maker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/itstudioat/hpm.svg?style=flat-square)](https://packagist.org/packages/itstudioat/hpm)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/itstudioat/hpm/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/itstudioat/hpm/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/itstudioat/hpm.svg?style=flat-square)](https://packagist.org/packages/itstudioat/hpm)




## Installation

You can install the package via composer:

```bash
composer require itstudioat/hpm
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="hpm-all"
```

## Routes
Now you have your homepage routes in routes/vendor/hpm-folder.
Uncomment your base-route in routes/web.php
```bash
    /* HOMEPAGE ROUTES 
    Route::get('/', function () {
        return view('spa::homepage');
    });
    */
```

Insert to the composer.json following line:
```bash
    "autoload": {
        "psr-4": {
            ...
            "Itstudioat\\Hpm\\": "vendor/itstudioat/hpm/"
        }
```


## Usage

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Guenther Kron](https://github.com/itstudioat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
