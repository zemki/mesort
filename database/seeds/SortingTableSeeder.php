<?php

use Illuminate\Database\Seeder;

class SortingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sortings')->insert([
            'name' => 'Circle Sorting',
            'description' => 'First circle sorting',
        ]);

        DB::table('sortings')->insert([
            'name' => 'Network Sorting',
            'description' => 'Network sorting',
        ]);

        DB::table('sortings')->insert([
            'name' => 'Qsort',
            'description' => 'Qsort',
        ]);
    }
}
