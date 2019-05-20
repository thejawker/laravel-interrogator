# Just Ask For What You Want


[![Latest Version on Packagist](https://img.shields.io/packagist/v/thejawker/laravel-interrogator.svg?style=flat-square)](https://packagist.org/packages/thejawker/laravel-interrogator)

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


#### Default filters
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


#### Null Filter
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

### Sorting
Sorting works according to the JSON spec by using the `sort` parameter on the request with the column name as a value. 

You can sort by **asc**:
```http request
GET http://example.com/api/users?sort=name
```

You can also sort by **descending** by adding a hyphen `-`:
```http request
GET http://example.com/api/users?sort=-name
```

#### Security
Like filtering there's some security baked in to the package. You can allow certain sorting columns by adding `->allowSortBy(['email'])` onto the Interrogator.

```php
interrogate(User::query())
    ->request($request)
    ->allowSortBy(['email'])
    ->get();   
```

#### Default sorting
Also default sorting can be defined on the `Interrogator`. Like the filtering; this is added on the request.

```php
public function get() {
    return interrogate(User::class)
        ->defaultSortBy('-email')
        ->get();
}   
```

## Chain shortcuts
You can keep chaining on with the Interrogator. `Pagination` and `get` are made shortcuts allowing you to do the following.
```php
// Gets all the results from the database. 
interrogate(User::class)->get();

// Paginates the results using the Laravel paginator.
interrogate(User::class)->paginate(); 

// Gets the query on the Interrogator, you are 
// then free to work with it like expected.
interrogate(User::class)->query()->take(2); 
```

## Your Life Made Easy
The `interrogate()` helper function tries to make your life easier by allowing various types.
We try to solve your intent when you enter one of the following types.

```php
// Illuminate\Database\Eloquent\Builder
interrogate(User::query());

// Illuminate\Database\Eloquent\Builder
interrogate(User::whereAdmin(true));

// Illuminate\Database\Eloquent\Relations\Relation;
interrogate(User::first()->posts());

// Class contstant
interrogate(User::class);
``` 

## Alternatives
For everything is a really nice [Spatie package](https://github.com/spatie/laravel-query-builder). 
I needed to be able to do some math specific operations that were not supported in the mentioned package.

## Test

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
