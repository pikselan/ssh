<?php

namespace App\Imports;

use App\Models\Hspk;
use Maatwebsite\Excel\Concerns\ToModel;

class HspkImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Hspk([
            //
        ]);
    }
}
