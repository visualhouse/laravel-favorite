<?php

namespace Manzadey\LaravelFavorite\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Manzadey\LaravelFavorite\Contracts\FavoriteabilityContract;
use Manzadey\LaravelFavorite\Contracts\FavoriteableContract;
use Manzadey\LaravelFavorite\Models\Favorite;

/**
 * Add to a model that is favorite
 *
 * @see FavoriteableContract
 */
trait Favoriteable
{
    /**
     * @see FavoriteableContract::bootFavoriteable()
     */
    public static function bootFavoriteable() : void
    {
        static::deleted(static function(FavoriteableContract $model) {
            $model->favoriteable()->delete();
        });
    }

    /**
     * @see FavoriteableContract::favoriteable()
     */
    public function favoriteable() : MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    /**
     * @see FavoriteableContract::addFavorite()
     */
    public function addFavorite(FavoriteabilityContract $user) : Model
    {
        return $this->favoriteable()->firstOrCreate([
            'user_id' => $user->id,
        ]);
    }

    /**
     * @see FavoriteableContract::isFavorite()
     */
    public function isFavorite(FavoriteabilityContract $user) : bool
    {
        return $this->favoriteable()->where('user_id', $user->id)->exists();
    }

    /**
     * @see FavoriteableContract::isFavorites()
     */
    public function isFavorites() : bool
    {
        return $this->favoriteable()->exists();
    }

    /**
     * @see FavoriteableContract::removeFavorite()
     */
    public function removeFavorite(FavoriteabilityContract $user) : void
    {
        $this->favoriteable()->where('user_id', $user->id)->delete();
    }

    /**
     * @see FavoriteableContract::toggleFavorite()
     */
    public function toggleFavorite(FavoriteabilityContract $user) : bool
    {
        $this->isFavorite($user) ? $this->removeFavorite($user) : $this->addFavorite($user);

        return $this->isFavorite($user);
    }

    /**
     * @see FavoriteableContract::favoriteBy()
     */
    public function favoriteBy() : Collection
    {
        return $this->favoriteable()->with('user')
            ->get()
            ->map(static fn(Favorite $favorite) => $favorite->getRelation('user'))
            ->filter();
    }
}
