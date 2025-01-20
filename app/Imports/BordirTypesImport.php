<?php

namespace App\Imports;

use App\Models\BordirType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BordirTypesImport implements ToModel, WithStartRow
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
        return new BordirType([
            'bordir_no' => $row[1],
            'bordir_type' => $row[2],
        ]);
    }
}
