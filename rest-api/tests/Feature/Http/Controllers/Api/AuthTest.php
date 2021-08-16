<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $user = $this->init();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => '12345'
        ]);

        $response->assertStatus(200);
    }

    public function test_logout()
    {
        $user = $this->init();

        $this->login($user);

        $responseLogout = $this->post(route('auth.logout'), [
            'email' => $user->email
        ], $this->header);

        $responseLogout->assertStatus(200);
    }
}
