<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'tags' => Tag::class,
            'comments' => Comment::class,
            'blogs' => Blog::class,
            'pages' => Page::class
        ]);
    }
}
