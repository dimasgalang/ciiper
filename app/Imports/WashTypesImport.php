<?php

namespace App\Imports;

use App\Models\WashType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class WashTypesImport implements ToModel, WithStartRow
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
        return new WashType([
            'wash_no' => $row[1],
            'wash_type' => $row[2],
        ]);
    }
}
