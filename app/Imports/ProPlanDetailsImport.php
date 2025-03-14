<?php

namespace App\Imports;

use App\Models\ProPlanDetail;
use Maatwebsite\Excel\Concerns\ToModel;

class ProPlanDetailsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ProPlanDetail([
            //
        ]);
    }
}
