<?php

namespace App\Imports;

use App\Models\FollowUp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FollowUpsImport implements ToModel, WithStartRow
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
        return new FollowUp([
            'fu_no' => $row[1],
            'fu_name' => $row[2],
        ]);
    }
}
