<?php

namespace Visualhouse\LaravelFavorite\Test\Models;

use Visualhouse\LaravelFavorite\Contracts\FavoriteabilityContract;
use Visualhouse\LaravelFavorite\Traits\Favoriteability;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FavoriteabilityContract
{
    use Favoriteability;

    protected $guarded = [];
}
