# Exposes a module concept to enforce a CRUD and Restful way of routing and naming convention.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thejawker/laravel-route-module-macro.svg?style=flat-square)](https://packagist.org/packages/thejawker/laravel-route-module-macro)
[![Build Status](https://img.shields.io/travis/thejawker/laravel-route-module-macro/master.svg?style=flat-square)](https://travis-ci.org/thejawker/laravel-route-module-macro)
[![Quality Score](https://img.shields.io/scrutinizer/g/thejawker/laravel-route-module-macro.svg?style=flat-square)](https://scrutinizer-ci.com/g/thejawker/laravel-route-module-macro)
[![Total Downloads](https://img.shields.io/packagist/dt/thejawker/laravel-route-module-macro.svg?style=flat-square)](https://packagist.org/packages/thejawker/laravel-route-module-macro)

This package works with Laravel 5.5. It is very much inspired by the great [Freek van der Herten](https://twitter.com/freekmurze) with their [Blender Package](https://github.com/spatie/blender/blob/master/app/Providers/RouteServiceProvider.php), where he has a Macro for a module.
This really resonates with the CRUD/Restful approach on routing.

## Installation

Require the package from Composer:

``` bash
composer require thejawker/laravel-route-module-macro
```

As of Laravel 5.5 it will magically register the package.

## Usage

You can add `Route::module('name', ['only'](optional), ['options](optional))` in any of your routes files. 
The second parameter will allow you to `only` use specific actions, and the third being general options. Refer to the [Laravel docs](https://laravel.com/docs/5.4/controllers#resource-controllers) for those.  

### Examples:

#### Full Resource
routes/api.php
```php
Route::module('posts');
```  

```bash
$ php artisan route:list
+-----------+-----------------------------------------+---------------+---------------------------------------------------------------------------+
| Method    | URI                                     | Name          | Action                                                                    |
+-----------+-----------------------------------------+---------------+---------------------------------------------------------------------------+
| POST      | api/posts                               | posts.store   | App\Http\Controllers\PostsController@store                                |
| GET|HEAD  | api/posts                               | posts.index   | App\Http\Controllers\PostsController@index                                |
| GET|HEAD  | api/posts/create                        | posts.create  | App\Http\Controllers\PostsController@create                               |
| DELETE    | api/posts/{post}                        | posts.destroy | App\Http\Controllers\PostsController@destroy                              |
| PUT|PATCH | api/posts/{post}                        | posts.update  | App\Http\Controllers\PostsController@update                               |
| GET|HEAD  | api/posts/{post}                        | posts.show    | App\Http\Controllers\PostsController@show                                 |
| GET|HEAD  | api/posts/{post}/edit                   | posts.edit    | App\Http\Controllers\PostsController@edit                                 |
+-----------+-----------------------------------------+---------------+---------------------------------------------------------------------------+
```

#### Only Resource
routes/api.php
```php
Route::module('posts', ['store']);
```  

```bash
$ php artisan route:list
+-----------+-----------------------------------------+---------------+----------------------------------------------------------------------------+
| Method    | URI                                     | Name          | Action                                                                     |
+-----------+-----------------------------------------+---------------+----------------------------------------------------------------------------+
| POST      | api/posts                               | posts.store   | App\Http\Controllers\PostsController@store                                 |
+-----------+-----------------------------------------+---------------+----------------------------------------------------------------------------+
```

#### Nested Resources
This will enforce you to write Controllers that make sense. A nested `users.posts` will require you to create a UserPostsController with the required actions.
routes/api.php
```php
Route::module('users.posts');
```  

```bash
$ php artisan route:list
+-----------+-----------------------------------------+---------------------+----------------------------------------------------------------------------+
| Method    | URI                                     | Name                | Action                                                                     |
+-----------+-----------------------------------------+---------------------+----------------------------------------------------------------------------+
| POST      | api/users/{user}/posts                  | users.posts.store   | App\Http\Controllers\UserPostsController@store                             |
| GET|HEAD  | api/users/{user}/posts                  | users.posts.index   | App\Http\Controllers\UserPostsController@index                             |
| GET|HEAD  | api/users/{user}/posts/create           | users.posts.create  | App\Http\Controllers\UserPostsController@create                            |
| DELETE    | api/users/{user}/posts/{post}           | users.posts.destroy | App\Http\Controllers\UserPostsController@destroy                           |
| PUT|PATCH | api/users/{user}/posts/{post}           | users.posts.update  | App\Http\Controllers\UserPostsController@update                            |
| GET|HEAD  | api/users/{user}/posts/{post}           | users.posts.show    | App\Http\Controllers\UserPostsController@show                              |
| GET|HEAD  | api/users/{user}/posts/{post}/edit      | users.posts.edit    | App\Http\Controllers\UserPostsController@edit                              |
+-----------+-----------------------------------------+---------------------+----------------------------------------------------------------------------+

```


## Test

This package definitely needs some extensive testing.

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
