<?php

namespace App\Imports;

use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BuyersImport implements ToModel, WithStartRow
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
        return new Buyer([
            'buyer_no' => $row[1],
            'buyer_name' => $row[2],
            'buyer_address' => $row[3],
            'buyer_contact' => $row[4],
        ]);
    }
}
