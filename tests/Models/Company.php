<?php

namespace Visualhouse\LaravelFavorite\Test\Models;

use Visualhouse\LaravelFavorite\Contracts\FavoriteableContract;
use Visualhouse\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model implements FavoriteableContract
{
    use Favoriteable;

    protected $guarded = [];
}
