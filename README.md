# Laravel Favorite (Laravel 8 Package)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Packagist Downloads][ico-downloads]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

**Allows Laravel Eloquent models to implement a 'favorite' or 'remember' or 'follow' feature.**

## Index

- [Installation](#installation)
- [Models](#models)
- [Usage](#usage)
- [Testing](#testing)
- [Change log](#change-log)
- [Contributions](#contributions)
	- [Pull Requests](#pull-requests)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

1) Install the package via Composer

```bash
$ composer require manzadey/laravel-favorite
```

2) You need to publish the migration to create the `favorites` table:

```shell
php artisan vendor:publish --provider="Manzadey\LaravelFavorite\FavoriteServiceProvider" --tag="migrations"
```

3) After that, you need to run migrations:

```shell
php artisan migrate
```

## Publishing the config file
Publishing the config file is optional:
```shell
php artisan vendor:publish --provider="Manzadey\LaravelFavorite\FavoriteServiceProvider" --tag="config"
```

This is the default content of the config file:
```php
declare(strict_types=1);

return [
    
    /*
     * The fully qualified class name of the favorite model.
     */
    'model' => \Manzadey\LaravelFavorite\Models\Favorite::class,
    
];
```

## Models

Your User model should import the `Favoriteability` trait, `FavoriteabilityContract` implements interface and use it, that trait allows the user to favorite the models.
(see an example below):

```php
use Manzadey\LaravelFavorite\Traits\Favoriteability;
use Manzadey\LaravelFavorite\Contracts\FavoriteabilityContract;

class User extends Authenticatable implements FavoriteabilityContract;
{
	use Favoriteability;
}
```

Your models should import the `Favoriteable` trait, `FavoriteableContract` implements interface and use it, that trait has the methods that you'll use to allow the model be favoriteable.
In all the examples I will use the Post model as the model that is 'Favoriteable', that's for example proposes only.
(see an example below):

```php
use Manzadey\LaravelFavorite\Traits\Favoriteable;
use Manzadey\LaravelFavorite\Contracts\FavoriteableContract;

class Post extends Model implements FavoriteableContract
{
    use Favoriteable;
}
```

That's it ... your model is now **"favoriteable"**!
Now the User can favorite models that have the favoriteable trait.

## Usage

The models can be favorite with and without an authenticated user
(see examples below):

### Add to favorites and remove from favorites:

If a param is passed in the favorite method, then the model will assume the user with that user model.

```php
$user = User::first();
$post = Post::find(1);
$post->addFavorite($user); // user with that id added to favorites this post
$post->removeFavorite($user); // user with that id removed from favorites this post
$post->toggleFavorite($user); // user with that id toggles the favorite status from this post
```

The user model can also add to favorites and remove from favorites:

```php
$user = User::first();
$post = Post::first();
$user->addFavorite($post); // The user added to favorites this post
$user->removeFavorite($post); // The user removed from favorites this post
$user->toggleFavorite($post); // The user toggles the favorite status from this post
```

### Return the favorite objects for the user:

A user can return the objects he marked as favorite.
You just need to pass the **class** in the `getFavorite()` method in the `User` model.

```php
$user = Auth::user();
$user->getFavorite(Post::class, Article::class); // returns a collection with the Posts the User marked as favorite
```

### Return the users who marked this object as favorite

You can return the users who marked this object, you just need to call the `favoriteBy()` method in the object

```php
$post = Post::find(1);
$post->favoriteBy(); // returns a collection with the Users that marked the post as favorite.
```

### Check if the user already favorite an object

You can check if the Auth user have already favorite an object, you just need to call the `isFavorite()` method in the object

```php
$post = Post::find(1);
$post->isFavorite(); // returns a boolean with true or false.
```

## Testing

The package have integrated testing, so everytime you make a pull request your code will be tested.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributions

Contributions are **welcome** and will be fully **credited**.
We accept contributions via Pull Requests on [Github](https://github.com/Manzadey/laravel-favorite).

### Pull Requests

- **[PSR-12 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-12-extended-coding-style-guide.md)** - Check the code style with ``$ composer check-style`` and fix it with ``$ composer fix-style``.

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.

- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.

- **Create feature branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

## Security

Please report any issue you find in the issues page.
Pull requests are welcome.

## Credits

- [Christian Kuri][link-author]
- [Manzadey Andrey][link-author-2]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/Manzadey/laravel-favorite.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://app.travis-ci.com/Manzadey/laravel-favorite.svg?branch=master
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Manzadey/laravel-favorite.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Manzadey/laravel-favorite.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Manzadey/laravel-favorite.svg?style=flat-square
[ico-php-version]: https://img.shields.io/packagist/php-v/manzadey/laravel-favorite?style=flat-square

[link-packagist]: https://packagist.org/packages/Manzadey/laravel-favorite
[link-travis]: https://travis-ci.org/Manzadey/laravel-favorite
[link-scrutinizer]: https://scrutinizer-ci.com/g/Manzadey/laravel-favorite/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Manzadey/laravel-favorite
[link-downloads]: https://packagist.org/packages/Manzadey/laravel-favorite
[link-author]: https://github.com/ChristianKuri
[link-author-2]: https://github.com/Manzadey
[link-contributors]: ../../contributors
