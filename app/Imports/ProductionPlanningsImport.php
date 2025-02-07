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
            'plan_no' => $row[1],
            'order_trans' => $row[2],
            'order_list' => $row[3],
            'has_sample' => $row[4],
            'has_mi' => $row[5],
            'has_cart' => $row[6],
            'fab_date' => $row[7],
            'acc_date' => $row[8],
            'bordir_approve' => $row[9],
            'pattern_date' => $row[10],
            'sampletest_date' => $row[11],
            'marker_date' => $row[12],
            'pilotrun_date' => $row[13],
            'ppm_date' => $row[14],
            'startcut_date' => $row[15],
            'finishcut_date' => $row[16],
            'startsew_date' => $row[17],
            'finishsew_date' => $row[18],
            'finishpack_date' => $row[19],
            'remark' => $row[20],
        ]);
    }
}
