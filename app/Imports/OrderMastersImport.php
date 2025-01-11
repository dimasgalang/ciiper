<?php

namespace App\Imports;

use App\Models\OrderMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrderMastersImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function startRow(): int
    {
       return 2;
    }

    public function model(array $row)
    {
        return new OrderMaster([
            'order_trans' => $row[1],
            'season_no' => $row[2],
            'buyer_no' => $row[3],
            'brand_no' => $row[4],
            'style_no' => $row[5],
            'po_no' => $row[6],
            'qty_order' => $row[7],
            'qty_ocf' => $row[8],
            'qty_gmt' => $row[9],
            'qty_sbd' => $row[10],
            'fu_no' => $row[11],
            'wash_type' => $row[12],
            'remark' => $row[13],
            'sketch_file' => $row[14],
        ]);
    }
}
