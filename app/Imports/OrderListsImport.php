<?php

namespace App\Imports;

use App\Models\OrderList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrderListsImport implements ToModel, WithStartRow
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
        return new OrderList([
            'order_trans' => $row[1],
            'order_list' => $row[2],
            'factory_no' => $row[3],
            'lot_no' => $row[4],
            'pobuyer_no' => $row[5],
            'dcpo_qty' => $row[6],
            'ex_factory_date' => $row[7],
            'vsl_date' => $row[8],
            'status' => $row[9],
        ]);
    }
}
