<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin'], //Do Everything
            ['name' => 'owner'], //Create, Update, Delete & Publish Posts & Page
            ['name' => 'manager'], //Update, Delete & Publish Post & Page
            ['name' => 'author'], //Create, Update Post & Page
            ['name' => 'subscribe'] //No Access CRUD
        ]);
    }
}
