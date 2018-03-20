# Interrogates the request and applies JSON API rules to the Laravel Query Builder.


[![Latest Version on Packagist](https://img.shields.io/packagist/v/thejawker/laravel-interrogator.svg?style=flat-square)](https://packagist.org/packages/thejawker/laravel-interrogator)
[![Build Status](https://img.shields.io/travis/thejawker/laravel-interrogator/master.svg?style=flat-square)](https://travis-ci.org/thejawker/laravel-interrogator)
[![Quality Score](https://img.shields.io/scrutinizer/g/thejawker/laravel-interrogator.svg?style=flat-square)](https://scrutinizer-ci.com/g/thejawker/laravel-interrogator)
[![Total Downloads](https://img.shields.io/packagist/dt/thejawker/laravel-interrogator.svg?style=flat-square)](https://packagist.org/packages/thejawker/laravel-interrogator)

## Installation

Require the package from Composer:

``` bash
composer require thejawker/laravel-interrogator
```

## Usage

You can `interrogate()` any Laravel Model in a Controller. Basic setup is easy peasy but does not provide proper security.
Security can be added where needed.

```
// App/Http/Controllers/UserController.php

// GET: /users?filters[name]=john*&sort=-name
// Returns the Users where the name starts with John and is sorted DESC by name. 
public function get() {
    return interrogate(User::builder)->get();
}

```

## Test

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
