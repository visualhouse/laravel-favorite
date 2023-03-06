<?php

namespace Visualhouse\LaravelFavorite\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Visualhouse\LaravelFavorite\Contracts\FavoriteabilityContract;
use Visualhouse\LaravelFavorite\Contracts\FavoriteableContract;

/**
 * @see FavoriteabilityContract
 */
trait Favoriteability
{
    public static function bootFavoriteability(): void
    {
        static::deleted(static function (FavoriteabilityContract $model) {
            $model->favorites()->delete();
        });
    }

    /**
     * @see FavoriteabilityContract::favorites()
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(config('favorite.model'));
    }

    /**
     * @see FavoriteabilityContract::addFavorite()
     */
    public function addFavorite(FavoriteableContract $model): Model
    {
        return $model->addFavorite($this);
    }

    /**
     * @see FavoriteabilityContract::getFavorite()
     */
    public function getFavorite(...$classes): Collection
    {
        return $this->favorites()
            ->when(count($classes) > 0, static fn (Builder $builder): Builder => $builder->whereIn('favoriteable_type', $classes))
            ->with('favoriteable')
            ->get()
            ->map(static fn ($favorite): FavoriteableContract => $favorite->getRelation('favoriteable'));
    }

    /**
     * @see FavoriteabilityContract::removeFavorite()
     */
    public function removeFavorite(FavoriteableContract $model): void
    {
        $model->removeFavorite($this);
    }

    /**
     * @see FavoriteabilityContract::toggleFavorite()
     */
    public function toggleFavorite(FavoriteableContract $model): bool
    {
        return $model->toggleFavorite($this);
    }

    /**
     * @see FavoriteabilityContract::isFavorite()
     */
    public function isFavorite(FavoriteableContract $model): bool
    {
        return $model->isFavorite($this);
    }
}
