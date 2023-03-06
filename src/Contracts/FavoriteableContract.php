<?php

namespace Visualhouse\LaravelFavorite\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * Add to a model that is electable
 */
interface FavoriteableContract
{
    /**
     * MorphMany Relationship
     */
    public function favoriteable(): MorphMany;

    /**
     * Add the current model to favorites of the specified user
     */
    public function addFavorite(FavoriteabilityContract $user): Model;

    /**
     * Check the current model whether it is a favorite of the specified user
     */
    public function isFavorite(FavoriteabilityContract $user): bool;

    /**
     * Check the current model whether it is a favorite
     */
    public function isFavorites(): bool;

    /**
     * Delete the current module from the favorites of the specified user
     */
    public function removeFavorite(FavoriteabilityContract $user): void;

    /**
     * The switch of the favorites of the specified user
     */
    public function toggleFavorite(FavoriteabilityContract $user): bool;

    /**
     * Return a collection with the Users who marked as favorite this model.
     */
    public function favoriteBy(): Collection;
}
