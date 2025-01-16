<?php

namespace App\Imports;

use App\Models\ProductionDept;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductionDeptsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ProductionDept([
            //
        ]);
    }
}
