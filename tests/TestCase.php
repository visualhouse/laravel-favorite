<?php

namespace Manzadey\LaravelFavorite\Test;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Schema\Blueprint;
use Manzadey\LaravelFavorite\Test\Models\Company;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Manzadey\LaravelFavorite\FavoriteServiceProvider;
use Manzadey\LaravelFavorite\Models\Favorite;
use Manzadey\LaravelFavorite\Test\Models\Article;
use Manzadey\LaravelFavorite\Test\Models\Post;
use Manzadey\LaravelFavorite\Test\Models\User;

abstract class TestCase extends OrchestraTestCase
{
    protected Collection $articles;

    protected Collection $posts;

    protected Collection $users;

    protected Collection $companies;

    public function setUp() : void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->articles  = Article::all();
        $this->posts     = Post::all();
        $this->users     = User::all();
        $this->companies = Company::all();
    }

    protected function getPackageProviders($app) : array
    {
        return [
            FavoriteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) : void
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => $this->getTempDirectory() . '/database.sqlite',
            'prefix'   => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    protected function setUpDatabase() : void
    {
        $this->resetDatabase();

        $this->CreateFavoritesTable();

        $this->createTables('articles', 'posts', 'companies', 'users');
        $this->seedModels(Article::class, Post::class, Company::class, User::class);
    }

    protected function resetDatabase() : void
    {
        file_put_contents($this->getTempDirectory() . '/database.sqlite', null);
    }

    protected function CreateFavoritesTable() : void
    {
        (include __DIR__ . '/../database/migrations/create_favorites_table.php')->up();
    }

    public function getTempDirectory() : string
    {
        return __DIR__ . '/temp';
    }

    protected function createTables(...$tableNames) : void
    {
        collect($tableNames)
            ->each(fn($tableName) => $this->app['db']->connection()->getSchemaBuilder()->create($tableName, function(Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }));
    }

    protected function seedModels(...$modelClasses) : void
    {
        collect($modelClasses)->each(function($modelClass) {
            foreach (range(1, 10) as $index) {
                $modelClass::create(['name' => "name {$index}"]);
            }
        });
    }
}
