<?php

namespace Tests;

use App\Models\Contact;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $header;

    protected function init()
    {
        Artisan::call('passport:install');
        $this->seed(RoleSeeder::class);

        $role = Role::where('name', '=', 'admin')
            ->first();

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Contact::factory()->create([
            'user_id' => $user->id
        ]);

        return $user;
    }

    protected function login($user)
    {
        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => '12345'
        ]);

        $data = $response->json();

        $this->header = [
            'Authorization' => 'Bearer ' . $data['token']
        ];

        return $data;
    }
}
