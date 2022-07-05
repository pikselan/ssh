<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Hspk;
use App\Imports\HspkImport;

use Session;

function IsNotNullOrEmptyString($str){
    return (isset($str) || trim($str) !== '' || $str !== NULL);
}

class HspkController extends Controller
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
		$file->move('file_hspk',$nama_file);

		// import data to db
		$data = Excel::toArray(new HspkImport, public_path('/file_hspk/'.$nama_file));

		// insert data to db
		$col_a = NULL;
		$nomor_turunan = 0;
		$time = Carbon::now()->format('Y F His');
		foreach(array_keys($data) as $name) {
				if($name !== 'SSH') {
						foreach($data[$name] as $row) {
								if (IsNotNullOrEmptyString($row['0']) && IsNotNullOrEmptyString($row['1'])) {
									$col_a = $row['0'];
									$nomor_turunan = 0;
									$arr[] = [
										// 'peraturan_id' => $row['peraturan_id'],
										'col_a' => $row['0'],
										'col_b' => $row['1'],
										'col_c' => $row['2'],
										'col_d' => $row['3'],
										'col_e' => $row['4'],
										'col_f' => $row['5'],
										'col_g' => $row['6'],
										'col_h' => $row['7'],
										'kode_turunan' => NULL,
										'periode' => $time,
									];
								} else {
									if($col_a !== NULL) {
										$nomor_turunan++;
										$arr[] = [
											// 'peraturan_id' => $row['peraturan_id'],
											'col_a' => $col_a,
											'col_b' => $row['1'],
											'col_c' => $row['2'],
											'col_d' => $row['3'],
											'col_e' => $row['4'],
											'col_f' => $row['5'],
											'col_g' => $row['6'],
											'col_h' => $row['7'],
											'kode_turunan' => $nomor_turunan,
											'periode' => $time,
										];
									}
								}
						}
				}
		}
		Hspk::query()->insert($arr);

		// notifikasi dengan session
		Session::flash('sukses','Data HSPK Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/admin/hspk');
	}
}
