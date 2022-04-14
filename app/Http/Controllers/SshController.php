<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

use App\Ssh;
use App\Imports\SshImport;
// use App\Exports\SshExport;

use Session;

class SshController extends Controller
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
		$file->move('file_ssh',$nama_file);

		// import data
		Excel::import(new SshImport, public_path('/file_ssh/'.$nama_file));

		// notifikasi dengan session
		Session::flash('sukses','Data SSH Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/admin/ssh');
	}
}
