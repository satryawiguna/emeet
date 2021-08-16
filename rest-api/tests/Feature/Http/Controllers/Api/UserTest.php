<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_contact_me_update_with_password()
    {
        $user = $this->init();

        $this->login($user);

        $response = $this->put(route('user.contact.me'), [
            'password' => '87654321',
            'password_confirmation' => '87654321',
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'state' => $this->faker->country(),
            'region' => $this->faker->name(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'post_code' => $this->faker->postcode(),
            'phone_code' => $this->faker->randomNumber(2),
            'phone_number' => $this->faker->phoneNumber()
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_user_contact_me_update_without_password()
    {
        $user = $this->init();

        $this->login($user);

        $response = $this->put(route('user.contact.me'), [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'state' => $this->faker->country(),
            'region' => $this->faker->name(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'post_code' => $this->faker->postcode(),
            'phone_code' => $this->faker->randomNumber(2),
            'phone_number' => $this->faker->phoneNumber()
        ], $this->header);

        $response->assertStatus(200);
    }

    public function test_view_all_user_contact()
    {
        $user = $this->init();

        $this->login($user);

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        $response = $this->get(route('user.contacts'),
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_user_contact()
    {
        $user = $this->init();

        $this->login($user);

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        $response = $this->delete(route('user.contact.delete', ['id' => rand(2, 10)]), [],
            $this->header);

        $response->assertStatus(200);
    }

    public function test_delete_bulk_user_contact()
    {
        $user = $this->init();

        $this->login($user);

        User::factory()->count(9)
            ->create()->each(function ($user) {
                Contact::factory()->create([
                    'user_id' => $user->id
                ]);
            });

        $response = $this->delete(route('user.contact.bulk.delete'), [
            'ids' => [1,3,5]
        ],
            $this->header);

        $response->assertStatus(200);
    }
}
