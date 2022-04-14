<?php

namespace App\Imports;

use App\Ssh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

function IsNullOrEmptyString($str){
    return (isset($str) || trim($str) !== '');
}

class SshImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (IsNullOrEmptyString($row['produk_akun'])) {
            return new Ssh([
                //
                // 'peraturan_id' => $row['peraturan_id'],
                'produk_akun' => $row['produk_akun'],
                'produk_kelompok' => $row['produk_kelompok'],
                'produk_jenis' => $row['produk_jenis'],
                'produk_objek' => $row['produk_objek'],
                'produk_rincian_objek' => $row['produk_rincian_objek'],
                'produk_sub_rincian_objek' => $row['produk_sub_rincian_objek'],
                'produk_sub_sub_rincian_objek' => $row['produk_sub_sub_rincian_objek'],
                'produk_kode' => $row['produk_kode'],
                'belanja_akun' => $row['belanja_akun'],
                'belanja_kelompok' => $row['belanja_kelompok'],
                'belanja_jenis' => $row['belanja_jenis'],
                'belanja_objek' => $row['belanja_objek'],
                'belanja_rincian_objek' => $row['belanja_rincian_objek'],
                'belanja_sub_rincian_objek' => $row['belanja_sub_rincian_objek'],
                'belanja_kode' => $row['belanja_kode'],
                'nama' => $row['nama'],
                'spesifikasi' => $row['spesifikasi'],
                'satuan' => $row['satuan'],
                'harga' => $row['harga'],
                // 'zona' => $row['zona'],
                // 'jenis_data' => $row['jenis_data'],
                'periode' => $row['periode'],
            ]);
        }
    }
}