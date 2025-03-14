<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetupIncrementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('setup_increment')->insert([
            'models' => 'Accesories',
            'last_number' => 'ACC000000025'
        ]);
        DB::table('setup_increment')->insert([
            'models' => 'Category',
            'last_number' => 'CAT000000006'
        ]);
        DB::table('setup_increment')->insert([
            'models' => 'ProductionDept',
            'last_number' => 'DEP000000004'
        ]);
        DB::table('setup_increment')->insert([
            'models' => 'Size',
            'last_number' => 'SIZ000000006'
        ]);
    }
}
