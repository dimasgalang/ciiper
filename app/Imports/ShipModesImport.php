<?php

namespace App\Imports;

use App\Models\ShipMode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShipModesImport implements ToModel, WithStartRow
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
        return new ShipMode([
            'ship_no' => $row[1],
            'ship_name' => $row[2],
        ]);
    }
}
