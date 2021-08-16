<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user)
    {
        $role = Role::whereIn('name', ['admin', 'owner', 'manager'])->get();
        $roleIds = $role->pluck('id');

        return in_array($user->role_id, $roleIds->toArray());
    }

    public function create(User $user)
    {
        $role = Role::whereIn('name', ['admin', 'owner', 'manager'])->get();
        $roleIds = $role->pluck('id');

        return in_array($user->role_id, $roleIds->toArray());
    }

    public function update(User $user)
    {
        $role = Role::whereIn('name', ['admin', 'owner', 'manager'])->get();
        $roleIds = $role->pluck('id');

        return in_array($user->role_id, $roleIds->toArray());
    }

    public function delete(User $user)
    {
        $role = Role::whereIn('name', ['admin', 'owner', 'manager'])->get();
        $roleIds = $role->pluck('id');

        return in_array($user->role_id, $roleIds->toArray());
    }
}
