<?php

namespace App\Imports;

use App\Models\ProductionPlanning;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductionPlanningsImport implements ToModel, WithStartRow
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
        return new ProductionPlanning([
            'order_trans' => $row[1],
            'order_list' => $row[2],
            'has_sample' => $row[3],
            'has_mi' => $row[4],
            'has_cart' => $row[5],
            'fab_date' => $row[6],
            'acc_date' => $row[7],
            'bordir_approve' => $row[8],
            'pattern_date' => $row[9],
            'sampletest_date' => $row[10],
            'marker_date' => $row[11],
            'pilotrun_date' => $row[12],
            'ppm_date' => $row[13],
            'startcut_date' => $row[14],
            'finishcut_date' => $row[15],
            'startsew_date' => $row[16],
            'finishsew_date' => $row[17],
            'finishpack_date' => $row[18],
            'remark' => $row[19],
        ]);
    }
}
