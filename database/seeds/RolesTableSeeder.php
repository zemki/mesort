<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'description' => 'user with all privileges',
        ]);

        DB::table('roles')->insert([
            'name' => 'researcher',
            'description' => 'Can create studies and make interviews',
        ]);
    }
}
