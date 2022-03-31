<?php

namespace Manzadey\LaravelFavorite\Test\Unit;

use Manzadey\LaravelFavorite\Contracts\FavoriteabilityContract;
use Manzadey\LaravelFavorite\Contracts\FavoriteableContract;
use Manzadey\LaravelFavorite\Models\Favorite;
use Manzadey\LaravelFavorite\Test\TestCase;

class FavoriteableModelTest extends TestCase
{
    public function testAddToFavourite() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->addToFavorite($user);
        $this->assertCount(1, $article->favoriteable);

        /* @var FavoriteableContract $post */
        $post = $this->posts->get(0);
        $post->addToFavorite($user);

        $this->assertCount(1, $post->favoriteable);

        $this->assertCount(2, $user->favorites);
    }

    public function testAddToFavoriteUsers() : void
    {
        /* @var FavoriteableContract $post */
        $post = $this->posts->get(0);

        $usersCount = 3;
        foreach ($this->users->take($usersCount) as $user) {
            $post->addToFavorite($user);
        }
        $post->load('favoriteable');
        $this->assertCount($usersCount, $post->favoriteable);
    }

    public function testAddToFavoriteDuplicated() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->addToFavorite($user);
        $article->addToFavorite($user);
        $article->addToFavorite($user);
        $this->assertCount(1, $article->favoriteable);

        /* @var FavoriteableContract $post */
        $post = $this->posts->get(0);
        $post->addToFavorite($user);
        $post->addToFavorite($user);
        $post->addToFavorite($user);

        $this->assertCount(1, $post->favoriteable);

        $this->assertCount(2, $user->favorites);
    }

    public function testIsFavoriteFromUser() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->addToFavorite($user);
        $this->assertTrue($article->isFavorite($user));

        /* @var FavoriteableContract $post */
        $post = $this->posts->get(0);
        $post->addToFavorite($user);
        $this->assertTrue($post->isFavorite($user));
    }

    public function testIsFavorites() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->addToFavorite($user);
        $this->assertTrue($article->isFavorites());

        /* @var FavoriteableContract $post */
        $post = $this->posts->get(0);
        $post->addToFavorite($user);
        $this->assertTrue($post->isFavorites());
    }

    public function testRemoveFavoriteFromUser() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->addToFavorite($user);
        $this->assertCount(1, $article->favoriteable);
        $article->removeFavorite($user);
        $article->load('favoriteable');
        $this->assertCount(0, $article->favoriteable);

        /* @var FavoriteableContract $post */
        $post = $this->posts->get(0);
        $post->addToFavorite($user);
        $this->assertCount(1, $post->favoriteable);
        $post->removeFavorite($user);
        $post->load('favoriteable');
        $this->assertCount(0, $post->favoriteable);
    }

    public function testToggleFavorite() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->toggleFavorite($user);
        $article->load('favoriteable');
        $this->assertCount(1, $article->favoriteable);

        $article->toggleFavorite($user);
        $article->load('favoriteable');
        $this->assertCount(0, $article->favoriteable);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(1);
        $this->assertTrue($article->toggleFavorite($user));
        $this->assertFalse($article->toggleFavorite($user));

        $this->assertTrue($user->toggleFavorite($article));
        $this->assertFalse($user->toggleFavorite($article));
    }

    public function testFavoriteBy() : void
    {
        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);

        /* @var FavoriteabilityContract $user */
        foreach ($this->users->take(10) as $user) {
            $article->addToFavorite($user);
        }

        $this->assertCount(10, $article->favoriteBy());
    }

    public function testFavoriteByWithDeletingUserModels() : void
    {
        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);

        $userIdsForDeleting = [1, 5, 10, 6];
        $countUsers         = 10;

        /* @var FavoriteabilityContract $user */
        foreach ($this->users->take($countUsers) as $user) {
            $article->addToFavorite($user);

            if(in_array($user->id, $userIdsForDeleting, true)) {
                $user->delete();
            }
        }

        $this->assertCount($countUsers - count($userIdsForDeleting), $article->favoriteBy());
    }

    public function testDeletedEvent() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        /* @var FavoriteableContract $article */
        $article = $this->articles->get(0);
        $article->addToFavorite($user);
        $this->assertCount(1, $article->favoriteable);

        $favoriteQuery = Favorite::where('user_id', $user->id)->where('favoriteable_type', $article->getMorphClass())->where('favoriteable_id', $article->id);
        $this->assertTrue($favoriteQuery->exists());

        $article->delete();
        $this->assertFalse($favoriteQuery->exists());
    }
}
