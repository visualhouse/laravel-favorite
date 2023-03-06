<?php

namespace Visualhouse\LaravelFavorite\Test\Unit;

use Visualhouse\LaravelFavorite\Contracts\FavoriteabilityContract;
use Visualhouse\LaravelFavorite\Models\Favorite;
use Visualhouse\LaravelFavorite\Test\Models\Article;
use Visualhouse\LaravelFavorite\Test\Models\Company;
use Visualhouse\LaravelFavorite\Test\Models\Post;
use Visualhouse\LaravelFavorite\Test\TestCase;

class FavoriteabilityModelTest extends TestCase
{
    public function testFavoriteFromModels() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        $this->assertCount(0, $user->favorites);

        $user->addFavorite($this->articles->get(0));
        $user->addFavorite($this->posts->get(0));

        $user->load('favorites');
        $this->assertCount(2, $user->favorites);
        $this->assertCount(1, $user->getFavorite(Article::class));
        $this->assertCount(2, $user->getFavorite(Article::class, Post::class));
        $this->assertCount(2, $user->getFavorite());

        $user->addFavorite($this->articles->get(1));
        $user->addFavorite($this->posts->get(1));

        $user->load('favorites');
        $this->assertCount(4, $user->favorites);
        $this->assertCount(2, $user->getFavorite(Article::class));
        $this->assertCount(2, $user->getFavorite(Post::class));
        $this->assertCount(4, $user->getFavorite(Article::class, Post::class));
        $this->assertCount(4, $user->getFavorite());

        $user->addFavorite($this->companies->get(0));

        $user->load('favorites');
        $this->assertCount(5, $user->favorites);
        $this->assertCount(2, $user->getFavorite(Article::class));
        $this->assertCount(2, $user->getFavorite(Post::class));
        $this->assertCount(5, $user->getFavorite(Article::class, Post::class, Company::class));
        $this->assertCount(5, $user->getFavorite());

        $user->addFavorite($this->companies->get(1));
        $this->assertCount(6, $user->getFavorite());
    }

    public function testFavoriteFromModelsWithDeletingModels() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        $this->assertCount(0, $user->favorites);

        $articlesCount = 10;
        foreach ($this->articles->take($articlesCount) as $article) {
            $user->addFavorite($article);
        }

        $user->load('favorites');
        $this->assertCount($articlesCount, $user->favorites);

        $articleDeleting = [1, 3, 5, 10];
        foreach ($user->getFavorite(Article::class) as $article) {
            if(in_array($article->id, $articleDeleting, true)) {
                $article->delete();
            }
        }

        $this->assertCount($articlesCount - count($articleDeleting), $user->getFavorite(Article::class));
    }

    public function testDeletedEvent() : void
    {
        /* @var FavoriteabilityContract $user */
        $user = $this->users->get(0);

        $this->assertCount(0, $user->favorites);

        $count = 10;
        foreach ($this->articles->take($count) as $item) {
            $user->addFavorite($item);
        }

        foreach ($this->posts->take($count) as $item) {
            $user->addFavorite($item);
        }

        $user->delete();

        $this->assertFalse(Favorite::where('user_id', $user->id)->exists());
    }
}
