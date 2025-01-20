<?php

namespace App\Imports;

use App\Models\Market;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MarketsImport implements ToModel, WithStartRow
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
        return new Market([
            'market_no' => $row[1],
            'market_name' => $row[2],
        ]);
    }
}
