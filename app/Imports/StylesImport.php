<?php

namespace App\Imports;

use App\Models\Style;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StylesImport implements ToModel, WithStartRow
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
        return new Style([
            'brand_no' => $row[1],
            'style_no' => $row[2],
            'style_name' => $row[3],
        ]);
    }
}
