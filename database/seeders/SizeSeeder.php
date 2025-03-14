<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('size')->insert([
            'size_no' => 'SIZ000000001',
            'size' => 'XS',
            'void' => 'false'
        ]);
        DB::table('size')->insert([
            'size_no' => 'SIZ000000002',
            'size' => 'S',
            'void' => 'false'
        ]);
        DB::table('size')->insert([
            'size_no' => 'SIZ000000003',
            'size' => 'M',
            'void' => 'false'
        ]);
        DB::table('size')->insert([
            'size_no' => 'SIZ000000004',
            'size' => 'L',
            'void' => 'false'
        ]);
        DB::table('size')->insert([
            'size_no' => 'SIZ000000005',
            'size' => 'XL',
            'void' => 'false'
        ]);
        DB::table('size')->insert([
            'size_no' => 'SIZ000000006',
            'size' => 'XXL',
            'void' => 'false'
        ]);
    }
}
