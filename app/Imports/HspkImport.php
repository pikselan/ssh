<?php

namespace App\Imports;

use App\Hspk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

function IsNotNullOrEmptyString($str){
    return (isset($str) || trim($str) !== '');
}

class HspkImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'SSH' => new SshSheetImport(),
            '1.Persiapan' => new DataSheetImport(),
            '2.Bongkaran' => new DataSheetImport(),
            '3.Tanah' => new DataSheetImport(),
            '4.Pondasi' => new DataSheetImport(),
            '5.Beton' => new DataSheetImport(),
            '6.Besi dan Alum' => new DataSheetImport(),
            // '7.Dinding' => new DataSheetImport(),
            // '8.Plesteran' => new DataSheetImport(),
            // '9.Penutup Lantai & Dinding' => new DataSheetImport(),
            // '10.Langit2' => new DataSheetImport(),
            // '11.Atap' => new DataSheetImport(),
            // '12.Kayu' => new DataSheetImport(),
            // '13.Kunci dan Kaca' => new DataSheetImport(),
            // '14.Pengecatan' => new DataSheetImport(),
            // '15.Sanitasi' => new DataSheetImport(),
            // '16.Elektrikal' => new DataSheetImport(),
        ];
    }
    
    // public function onUnknownSheet($sheetName)
    // {
    //     // E.g. you can log that a sheet was not found.
    //     info("Sheet {$sheetName} was skipped");
    // }
}

class SshSheetImport implements ToModel, HasReferencesToOtherSheets
{
    public function model(array $row)
    {
        //
    }
}

class DataSheetImport implements ToModel, SkipsEmptyRows, WithCalculatedFormulas
{
    public function model(array $row)
    {
        //
        if (IsNotNullOrEmptyString($row['0'])) {
            return new Hspk([
                // 'peraturan_id' => $row['peraturan_id'],
                'col_a' => $row['0'],
                'col_b' => $row['1'],
                'col_c' => $row['2'],
                'col_d' => $row['3'],
                'col_e' => $row['4'],
                'col_f' => $row['5'],
                'col_g' => $row['6'],
                'col_h' => $row['7'],
            ]);
        } else {
            return new Hspk([
                // 'peraturan_id' => $row['peraturan_id'],
                // 'col_a' => $row['0'],
                'col_b' => $row['1'],
                'col_c' => $row['2'],
                'col_d' => $row['3'],
                'col_e' => $row['4'],
                'col_f' => $row['5'],
                'col_g' => $row['6'],
                'col_h' => $row['7'],
            ]);
        }
    }
}