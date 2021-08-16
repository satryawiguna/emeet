<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_view_all_blog_category()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        $response = $this->get(route('blog-categories'),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_store_blog_category()
    {
        $user = $this->init();

        $this->login($user);

        $response = $this->post(route('blog-category.store'), [
            'name' => $this->faker->name
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_view_blog_category()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        $response = $this->get(route('blog-category.show', ['id' => rand(1, 10)]),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_update_blog_category()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        $response = $this->put(route('blog-category.update', ['id' => rand(1, 10)]), [
            'name' => $this->faker->name
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_blog_category()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        $response = $this->delete(route('blog-category.delete', ['id' => rand(1, 10)]), [],
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_bulk_blog_category()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        $response = $this->delete(route('blog-category.bulk.delete'), [
                'ids' => [1, 3, 5]
            ],
            $this->header);

        $response->assertStatus(200);
    }


    public function test_view_all_blog()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(5)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        Blog::all()->each(function($blog) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $blog->id,
                        'commentable_type' => 'blogs',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($blog) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $blog->id,
                        'taggable_type' => 'blogs'
                    ]
                );
            });
        });

        $response = $this->get(route('blogs'),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_store_blog()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(5)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->post(route('blog.store'), [
            'blog_category_id' => BlogCategory::all()->random()->id,
            'title' => $this->faker->text(25),
            'content' => $this->faker->text,
            'tags' => Tag::all()->random(5)->pluck('id')->toArray()
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_store_comment()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(5)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        Blog::all()->each(function($blog) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $blog->id,
                        'commentable_type' => 'blogs',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($blog) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $blog->id,
                        'taggable_type' => 'blogs'
                    ]
                );
            });
        });

        $response = $this->post(route('blog.id.comment.store', ['id' => rand(1, 10)]), [
            'message' => $this->faker->text
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_view_blog()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(5)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        Blog::all()->each(function($blog) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $blog->id,
                        'commentable_type' => 'blogs',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($blog) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $blog->id,
                        'taggable_type' => 'blogs'
                    ]
                );
            });
        });

        $response = $this->get(route('blog.show', ['id' => rand(1, 10)]),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_update_blog()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        Blog::all()->each(function($blog) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $blog->id,
                        'commentable_type' => 'blogs',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($blog) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $blog->id,
                        'taggable_type' => 'blogs'
                    ]
                );
            });
        });

        $response = $this->put(route('blog.update', ['id' => rand(1, 10)]), [
            'blog_category_id' => BlogCategory::all()->random()->id,
            'title' => $this->faker->text(25),
            'content' => $this->faker->realText,
            'tags' => Tag::all()->random(5)->pluck('id')->toArray()
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_blog()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        Blog::all()->each(function($blog) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $blog->id,
                        'commentable_type' => 'blogs',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($blog) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $blog->id,
                        'taggable_type' => 'blogs'
                    ]
                );
            });
        });

        $response = $this->delete(route('blog.delete', ['id' => rand(1, 10)]), [],
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_bulk_blog()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Tag::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        Blog::all()->each(function($blog) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $blog->id,
                        'commentable_type' => 'blogs',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($blog) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $blog->id,
                        'taggable_type' => 'blogs'
                    ]
                );
            });
        });

        $response = $this->delete(route('blog.bulk.delete'), [
            'ids' => [1, 3, 5]
        ],
            $this->header);

        $response->assertStatus(200);
    }



    public function test_view_all_comment()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        Comment::factory()
            ->count(10)
            ->create([
                'user_id' => User::all()->random()->id,
                'commentable_id' => Blog::all()->random()->id,
                'commentable_type' => 'blogs'
            ]);

        $response = $this->get(route('comments'),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_view_comment()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        Comment::factory()
            ->count(10)
            ->create([
                'user_id' => User::all()->random()->id,
                'commentable_id' => Blog::all()->random()->id,
                'commentable_type' => 'blogs'
            ]);

        $response = $this->get(route('comment.show', ['id' => rand(1, 10)]),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_update_comment()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        Comment::factory()
            ->count(10)
            ->create([
                'user_id' => User::all()->random()->id,
                'commentable_id' => Blog::all()->random()->id,
                'commentable_type' => 'blogs'
            ]);

        $response = $this->put(route('comment.update', ['id' => rand(1, 10)]), [
            'message' => $this->faker->text
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_comment()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        Comment::factory()
            ->count(10)
            ->create([
                'user_id' => User::all()->random()->id,
                'commentable_id' => Blog::all()->random()->id,
                'commentable_type' => 'blogs'
            ]);

        $response = $this->delete(route('comment.delete', ['id' => rand(1, 10)]), [],
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_bulk_comment()
    {
        $user = $this->init();

        $this->login($user);

        BlogCategory::factory()
            ->count(10)
            ->create();

        Blog::factory()
            ->count(10)
            ->create();

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        Comment::factory()
            ->count(10)
            ->create([
                'user_id' => User::all()->random()->id,
                'commentable_id' => Blog::all()->random()->id,
                'commentable_type' => 'blogs'
            ]);

        $response = $this->delete(route('comment.bulk.delete'), [
            'ids' => [1, 3, 5]
        ],
            $this->header);

        $response->assertStatus(200);
    }
}
