<?php

namespace App\Imports;

use App\Models\Factory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FactorysImport implements ToModel, WithStartRow
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
        return new Factory([
            'factory_no' => $row[1],
            'factory_name' => $row[2],
        ]);
    }
}
