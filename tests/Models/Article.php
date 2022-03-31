<?php

namespace Manzadey\LaravelFavorite\Test\Models;

use Manzadey\LaravelFavorite\Contracts\FavoriteableContract;
use Manzadey\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements FavoriteableContract
{
    use Favoriteable;

    protected $guarded = [];
}
