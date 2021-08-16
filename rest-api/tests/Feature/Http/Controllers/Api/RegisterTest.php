<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        Artisan::call('passport:install');
        $this->seed(RoleSeeder::class);

        $role = Role::where('name', '=', 'admin')
            ->first();

        $response = $this->post(route('register'), [
            'role_id' => $role->id,
            'email' => $this->faker->email(),
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'contact' => [
                'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
                'state' => $this->faker->country(),
                'region' => $this->faker->name(),
                'city' => $this->faker->city(),
                'address' => $this->faker->address(),
                'post_code' => $this->faker->postcode(),
                'phone_code' => $this->faker->randomNumber(2),
                'phone_number' => $this->faker->phoneNumber()
            ]
        ]);

        $response->assertStatus(200);
    }
}
