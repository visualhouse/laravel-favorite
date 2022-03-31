<?php

namespace Manzadey\LaravelFavorite\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * Add to a model that is electable
 */
interface FavoriteableContract
{
    /**
     * Add deleted observer to delete favorites registers
     *
     * @return void
     */
    public static function bootFavoriteable() : void;

    /**
     * MorphMany Relationship
     *
     * @return MorphMany
     */
    public function favoriteable() : MorphMany;

    /**
     * Add the current model to favorites of the specified user
     *
     * @param FavoriteabilityContract $user
     *
     * @return Model
     */
    public function addToFavorite(FavoriteabilityContract $user) : Model;

    /**
     * Check the current model whether it is a favorite of the specified user
     *
     * @param FavoriteabilityContract $user
     *
     * @return bool
     */
    public function isFavorite(FavoriteabilityContract $user) : bool;

    /**
     * Check the current model whether it is a favorite
     *
     * @return bool
     */
    public function isFavorites() : bool;

    /**
     * Delete the current module from the favorites of the specified user
     *
     * @param FavoriteabilityContract $user
     *
     * @return void
     */
    public function removeFavorite(FavoriteabilityContract $user) : void;

    /**
     * The switch of the favorites of the specified user
     *
     * @param FavoriteabilityContract $user
     *
     * @return bool
     */
    public function toggleFavorite(FavoriteabilityContract $user) : bool;

    /**
     * Return a collection with the Users who marked as favorite this model.
     *
     * @return Collection
     */
    public function favoriteBy() : Collection;
}
