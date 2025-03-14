<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccesoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000001',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'BENANG 40/3',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000002',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'BENANG 60/2',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000003',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'METAL COIN',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000004',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'METAL END',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000005',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'METAL EYELED',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000006',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'DRAWSTRING',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000007',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'QR BARCODE',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000008',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'WASH CARE',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000009',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'SIZE LABEL',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000010',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'ELASTIK',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000011',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'BUTTON',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000012',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'RIP',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000013',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'HANGER LOOP',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000014',
            'category_no' => 'CAT000000006',
            'accesories_name' => 'LYCRA TAPE',
            'void' => 'false'
        ]);


        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000015',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'HANGTAG',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000016',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'HANGTAG STUDIO',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000017',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'STRING HT',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000018',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'HT LOOFIT',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000019',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'STICKER HT',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000020',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'STICKER POLYBAG',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000021',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'POLYBAG',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000022',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'TISUE PAPER',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000023',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'LAYER',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000024',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'CARTON',
            'void' => 'false'
        ]);
        DB::table('accesories')->insert([
            'accesories_no' => 'ACC000000025',
            'category_no' => 'CAT000000005',
            'accesories_name' => 'LAKBAN',
            'void' => 'false'
        ]);
    }
}
