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

```php
// App/Http/Controllers/UserController.php

// GET: /users?filter[name]=john*&sort=-name
// Returns the Users where the name starts with John and is sorted DESC by name. 
public function get() {
    return interrogate(User::class)->get();
}

```

### Filtering
You can use filters to add where clauses to your query builder. A filter is depicted by the `filter[column]=value` variable.

#### Wildcard Filter
You can use wildcard operators in string filters. It is possible to create fairly complex filters this way.
```http request
GET http://example.com/api/sites?filter[url]=https*/thejawker/posts/*/images
```


#### List Filter
A comma-seperated list can be used as a filter. This will in turn run a `whereIn` on the `QueryBuilder`.

```http request
GET http://example.com/api/user?files[type]=jpg,png
```
>Note that you can currently **not** combine this with the wildcard filter as follows: `jpg,pn*`. 


#### And or Or
You can specify the select's `and` or `or` conditions. 

For example if you want to fetch `Users` with a gmail email address **AND** and a Dutch mobile phone numbers, you could do the following.
```http request
GET http://example.com/api/users?filter[email]=*gmail*&filter[phone]=[and]+31*
```

By default the `or` operator is used, however you are free to explicitly
```http request
GET http://example.com/api/users?filter[email]=*gmail*&filter[phone]=[or]+31*
```


#### Math Operations
It is possible to use a variety of different math operators in a filter.

| Operator | Math Operator | Name             | Example               |
|----------|:-------------:|------------------|-----------------------|
| ge       |       >=      | Greater or equal | filter[price]=[ge]500 |
| gt       |       >       | Greater than     | filter[price]=[gt]500 |
| le       |       <=      | Less or equal    | filter[price]=[le]500 |
| lt       |       <       | Less than        | filter[price]=[lt]500 |

Of course you can combine this with the **And or Or** operator.


### Default filters
It is possible to conveniently set default filters on the Interrogator. These can be overriden by the api consumer (client).
Under the hood it will set the filter to the request.
```php
interrogate(User::class)
    ->defaultFilters(['email' => '*@gmail.com'])
    ->get();
```

If you want you can filter the QueryBuilder beforehand as well.

```php
interrogate(User::whereType('admin'))
    ->get();
```


### Null Filter
Sometimes you need to make sure a column is null. You can for example do the following:
```http request
GET http://example.com/api/users?filter[profile_image]=[null]
```

#### Security
For some models you might want to prevent filtering on certain columns. The way you do this is by allowing a selection of fields.
A not allowed filter will throw an error.

```php
public function get() {
    return interrogate(User::class)
        ->allowFilters(['email', 'name'])
        ->get();
}   
```

>NOTE: nested filters are currently not supported.

## Test

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
