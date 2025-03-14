<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category')->insert([
            'category_name' => 'ACCESSORIES',
            'category_no' => 'CAT000000001',
            'void' => 'false'
        ]);
        DB::table('category')->insert([
            'category_name' => 'SAMPLE',
            'category_no' => 'CAT000000002',
            'void' => 'false'
        ]);
        DB::table('category')->insert([
            'category_name' => 'FABRIC',
            'category_no' => 'CAT000000003',
            'void' => 'false'
        ]);
        DB::table('category')->insert([
            'category_name' => 'MI',
            'category_no' => 'CAT000000004',
            'void' => 'false'
        ]);
        DB::table('category')->insert([
            'category_name' => 'PACKING',
            'category_no' => 'CAT000000005',
            'void' => 'false'
        ]);
        DB::table('category')->insert([
            'category_name' => 'SEWING',
            'category_no' => 'CAT000000006',
            'void' => 'false'
        ]);
    }
}
