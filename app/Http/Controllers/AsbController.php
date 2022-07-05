<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

use App\Asb;
use App\Imports\AsbImport;

use Session;

function IsNotNullOrEmptyString($str){
    return (isset($str) || trim($str) !== '' || $str !== NULL);
}

class AsbController extends Controller
{
    //
	public function import(Request $request) 
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_ssh di dalam folder public
		$file->move('file_asb',$nama_file);

		// import data to array
		$data = Excel::toArray(new AsbImport, public_path('/file_asb/'.$nama_file));

        // insert data to db
        $kode_kepala = NULL;
        $nomor_turunan = 0;
        foreach($data['ASB'] as $row) {
            if (IsNotNullOrEmptyString($row['no'])) {
                $kode_kepala = $row['no'];
                $nomor_turunan = 0;
                $arr[] = [
                    'kode' => $row['no'],
                    'kode_turunan' => NULL,
                    'nama' => $row['nama'],
                    'nilai' => $row['nilai'],
                    'periode' => $row['periode'],
                ];
            } else {
                $nomor_turunan++;
                $arr[] = [
                    'kode' => $kode_kepala,
                    'kode_turunan' => $nomor_turunan,
                    'nama' => $row['nama'],
                    'nilai' => $row['nilai'],
                    'periode' => $row['periode'],
                ];
            }
        }
        Asb::query()->insert($arr);

		// notifikasi dengan session
		Session::flash('sukses','Data ASB Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/admin/asbs');
	}
}
