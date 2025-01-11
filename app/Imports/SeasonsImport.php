<?php

namespace App\Imports;

use App\Models\Season;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SeasonsImport implements ToModel, WithStartRow
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
        return new Season([
            'season_no' => $row[1],
            'season_cat' => $row[2],
            'season_year' => $row[3],
        ]);
    }
}
