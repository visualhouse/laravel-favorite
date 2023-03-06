<?php

namespace Visualhouse\LaravelFavorite\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Add to a model that adds to favorites
 *
 * @see \Illuminate\Foundation\Auth\User
 */
interface FavoriteabilityContract
{
    /**
     * The relationship between the user and the favorites models
     */
    public function favorites(): HasMany;

    /**
     * Add model to favorite this user
     */
    public function addFavorite(FavoriteableContract $model): Model|bool;

    /**
     * Get favorite collection from this user
     */
    public function getFavorite(...$classes): Collection;

    /**
     * Remove favorite from this user
     */
    public function removeFavorite(FavoriteableContract $model): void;

    /**
     * The switch of the favorites of this user
     */
    public function toggleFavorite(FavoriteableContract $model): bool;

    /**
     * Checking the model - whether it is a favorite of this user
     */
    public function isFavorite(FavoriteableContract $model): bool;
}
