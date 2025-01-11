<?php

namespace App\Imports;

use App\Models\Fabrication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FabricationsImport implements ToModel, WithStartRow
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
        return new Fabrication([
            'order_trans' => $row[1],
            'fab_no' => $row[2],
            'fabmill_no' => $row[3],
            'fabrication' => $row[4],
            'po_fab' => $row[5],
            'etd' => $row[6],
        ]);
    }
}
