<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('production_dept')->insert([
            'dept_no' => 'DEP000000001',
            'dept_name' => 'CUTTING',
            'void' => 'false'
        ]);
        DB::table('production_dept')->insert([
            'dept_no' => 'DEP000000002',
            'dept_name' => 'SEWING',
            'void' => 'false'
        ]);
        DB::table('production_dept')->insert([
            'dept_no' => 'DEP000000003',
            'dept_name' => 'IRONING',
            'void' => 'false'
        ]);
        DB::table('production_dept')->insert([
            'dept_no' => 'DEP000000004',
            'dept_name' => 'PACKING',
            'void' => 'false'
        ]);
    }
}
