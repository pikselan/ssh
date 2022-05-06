<?php

namespace App\Imports;

use App\Asb;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

function IsNullOrEmptyString($str){
    return (isset($str) || trim($str) !== '');
}

class AsbImport implements WithMultipleSheets 
{
    public function sheets(): array
    {
        return [
            'DB' => new DbSheetImport(),
            'ASB' => new AsbSheetImport(),
        ];
    }
}

class DbSheetImport implements ToModel, HasReferencesToOtherSheets
{
    public function model(array $row)
    {
        //
    }
}

class AsbSheetImport implements ToModel, WithCalculatedFormulas, WithHeadingRow
{
    public function model(array $row)
    {
        if (IsNullOrEmptyString($row['no'])) {
            return new Asb([
                // 'peraturan_id' => $row['peraturan_id'],
                'kode' => $row['no'],
                'nama' => $row['nama'],
                'nilai' => $row['nilai'],
                'periode' => $row['periode'],
            ]);
        } else {
            return new Asb([
                // 'peraturan_id' => $row['peraturan_id'],
                // 'kode' => $this->kode_kepala,
                // 'kode_turunan' => $this->nomor_turunan,
                'nama' => $row['nama'],
                'nilai' => $row['nilai'],
                'periode' => $row['periode'],
            ]);
        }
    }
}