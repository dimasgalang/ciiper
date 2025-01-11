<?php

namespace App\Imports;

use App\Models\FabricMill;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FabricMillsImport implements ToModel, WithStartRow
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
        return new FabricMill([
            'fabmill_no' => $row[1],
            'fabmill_name' => $row[2],
        ]);
    }
}
