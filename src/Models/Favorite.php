<?php

namespace Manzadey\LaravelFavorite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public function favoriteable() : MorphTo
    {
        return $this->morphTo();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'));
    }
}
