<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('status')->insert([
            'name' => 'Active',
            'class'=> 'badge badge-success'
        ]);

        DB::table('status')->insert([
            'name' => 'Inactive',
            'class'=> 'badge badge-danger'
        ]);

        DB::table('status')->insert([
            'name' => 'Paused',
            'class'=> 'badge badge-warning'
        ]);

        DB::table('status')->insert([
            'name' => 'In-Progress',
            'class'=> 'badge badge-info'
        ]);
    }
}
