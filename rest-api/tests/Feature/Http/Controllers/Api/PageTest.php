<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PageTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_view_all_page()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        Page::factory()
            ->count(10)
            ->create();

        Page::all()->each(function($page) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $page->id,
                        'commentable_type' => 'pages',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($page) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $page->id,
                        'taggable_type' => 'pages'
                    ]
                );
            });
        });

        $response = $this->get(route('pages'),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_store_page()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->post(route('page.store'), [
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

        Tag::factory()
            ->count(10)
            ->create();

        Page::factory()
            ->count(10)
            ->create();

        Page::all()->each(function($page) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $page->id,
                        'commentable_type' => 'pages',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($page) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $page->id,
                        'taggable_type' => 'pages'
                    ]
                );
            });
        });

        $response = $this->post(route('page.id.comment.store', ['id' => rand(1, 10)]), [
            'message' => $this->faker->text
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_view_page()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        Page::factory()
            ->count(10)
            ->create();

        Page::all()->each(function($page) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $page->id,
                        'commentable_type' => 'pages',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($page) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $page->id,
                        'taggable_type' => 'pages'
                    ]
                );
            });
        });

        $response = $this->get(route('page.show', ['id' => rand(1, 10)]),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_update_page()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        Page::factory()
            ->count(10)
            ->create();

        Page::all()->each(function($page) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $page->id,
                        'commentable_type' => 'pages',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($page) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $page->id,
                        'taggable_type' => 'pages'
                    ]
                );
            });
        });

        $response = $this->put(route('page.update', ['id' => rand(1, 10)]), [
            'title' => $this->faker->text(25),
            'content' => $this->faker->realText,
            'tags' => Tag::all()->random(5)->pluck('id')->toArray()
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_page()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        Page::factory()
            ->count(10)
            ->create();

        Page::all()->each(function($page) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $page->id,
                        'commentable_type' => 'pages',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($page) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $page->id,
                        'taggable_type' => 'pages'
                    ]
                );
            });
        });

        $response = $this->delete(route('page.delete', ['id' => rand(1, 10)]), [],
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_bulk_page()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        Page::factory()
            ->count(10)
            ->create();

        Page::all()->each(function($page) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('comments')->insert(
                    [
                        'user_id' => User::all()->random()->id,
                        'commentable_id' => $page->id,
                        'commentable_type' => 'pages',
                        'message' => $this->faker->text
                    ]
                );
            }

            Tag::all()->random(5)->each(function($tag) use($page) {
                DB::table('taggables')->insert(
                    [
                        'tag_id' => $tag->id,
                        'taggable_id' => $page->id,
                        'taggable_type' => 'pages'
                    ]
                );
            });
        });

        $response = $this->delete(route('page.bulk.delete'), [
            'ids' => [1, 3, 5]
        ],
            $this->header);

        $response->assertStatus(200);
    }
}
