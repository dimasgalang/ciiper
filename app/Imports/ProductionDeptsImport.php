<?php

namespace App\Imports;

use App\Models\ProductionDept;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductionDeptsImport implements ToModel, WithStartRow
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
        return new ProductionDept([
            'dept_no' => $row[1],
            'dept_name' => $row[2],
        ]);
    }
}
