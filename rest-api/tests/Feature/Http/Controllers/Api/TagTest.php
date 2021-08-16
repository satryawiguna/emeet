<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_view_all_tag()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->get(route('tags'),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_store_tag()
    {
        $user = $this->init();

        $this->login($user);

        $response = $this->post(route('tag.store'), [
            'name' => $this->faker->name
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_view_tag()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->get(route('tag.show', ['id' => rand(1, 10)]),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_update_tag()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->put(route('tag.update', ['id' => rand(1, 10)]), [
            'name' => $this->faker->name
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_tag()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->delete(route('tag.delete', ['id' => rand(1, 10)]), [],
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_bulk_tag()
    {
        $user = $this->init();

        $this->login($user);

        Tag::factory()
            ->count(10)
            ->create();

        $response = $this->delete(route('tag.bulk.delete'), [
            'ids' => [1, 3, 5]
        ],
            $this->header);

        $response->assertStatus(200);
    }
}
