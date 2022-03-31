<?php

namespace Manzadey\LaravelFavorite\Test\Models;

use Manzadey\LaravelFavorite\Contracts\FavoriteabilityContract;
use Manzadey\LaravelFavorite\Traits\Favoriteability;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FavoriteabilityContract
{
    use Favoriteability;

    protected $guarded = [];
}
