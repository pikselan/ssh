<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ssh;

class ViewSshController extends Controller
{
    public function index(Request $request)
    {
        $req_select_periode = $request->get('select_periode');
        if(isset($req_select_periode)) {
            if(empty($req_select_periode)) {
                $arr_select_periode = Ssh::select('periode')->groupBy('periode')->first();
                $select_periode = $arr_select_periode->periode;
            } else {
                $select_periode = $req_select_periode;
            }
        } else {
            // $b = Ssh::select('periode')->groupBy('periode')->orderByDesc('id')->first();
            $arr_select_periode = Ssh::select('periode')->groupBy('periode')->first();
            $select_periode = $arr_select_periode->periode;
        };
        
        $list_periode = Ssh::select('periode')->groupBy('periode')->get();

        $list_kode_rincian_objek = Ssh::select("nama")
        ->selectRaw("CONCAT_WS('.',produk_akun,produk_kelompok,produk_jenis,LPAD(produk_objek, 2, 0),LPAD(produk_rincian_objek, 2, 0),LPAD(produk_sub_rincian_objek, 2, 0),LPAD(produk_sub_sub_rincian_objek, 3, 0),LPAD(produk_kode, 3, 0)) as kode_produk")
        ->selectRaw("CONCAT_WS('.',belanja_akun,belanja_kelompok,belanja_jenis,LPAD(belanja_objek, 2, 0),LPAD(belanja_rincian_objek, 2, 0),LPAD(belanja_sub_rincian_objek, 2, 0),LPAD(belanja_kode, 3, 0)) as kode_belanja")
        ->where('periode', 'like', '%' .$select_periode . '%')
        ->where('produk_sub_rincian_objek', null)
        ->get();

        $req_kode_sub_sub_rincian_objek = $request->get('kode_sub_sub_rincian_objek');
        if(isset($req_kode_sub_sub_rincian_objek)) {
            $kode_sub_sub_rincian_objek = $req_kode_sub_sub_rincian_objek;
        } else {
            $kode_sub_sub_rincian_objek = '';
        };

        return view('view-ssh.index', ['list_periode'=>$list_periode, 'select_periode'=>$select_periode, 'list_kode_rincian_objek'=>$list_kode_rincian_objek, 'kode_sub_sub_rincian_objek'=>$kode_sub_sub_rincian_objek]);
    }

    public function getSubRincianObjek(Request $request){
        $select_periode = $request->get('select_periode');
        $select_rincian_objek = explode('.', $request->get('select_rincian_objek'));

        $response['data'] = Ssh::select("*")
        ->selectRaw("CONCAT_WS('.',produk_akun,produk_kelompok,produk_jenis,LPAD(produk_objek, 2, 0),LPAD(produk_rincian_objek, 2, 0),LPAD(produk_sub_rincian_objek, 2, 0),LPAD(produk_sub_sub_rincian_objek, 3, 0),LPAD(produk_kode, 3, 0)) as kode_produk")
        ->selectRaw("CONCAT_WS('.',belanja_akun,belanja_kelompok,belanja_jenis,LPAD(belanja_objek, 2, 0),LPAD(belanja_rincian_objek, 2, 0),LPAD(belanja_sub_rincian_objek, 2, 0),LPAD(belanja_kode, 3, 0)) as kode_belanja")
        ->where('periode', 'like', '%' .$select_periode . '%')
        ->where('produk_akun', $select_rincian_objek[0])
        ->where('produk_kelompok', $select_rincian_objek[1])
        ->where('produk_jenis', $select_rincian_objek[2])
        ->where('produk_objek', $select_rincian_objek[3])
        ->where('produk_rincian_objek', $select_rincian_objek[4])
        ->whereNotNull('produk_sub_rincian_objek')
        ->whereNull('produk_sub_sub_rincian_objek')
        ->whereNull('produk_kode')
        ->get();

        return response()->json($response); 
    }

    public function getSubSubRincianObjek(Request $request){
        $select_periode = $request->get('select_periode');
        $select_sub_rincian_objek = explode('.', $request->get('select_sub_rincian_objek'));

        $response['data'] = Ssh::select("nama")
        ->selectRaw("CONCAT_WS('.',produk_akun,produk_kelompok,produk_jenis,LPAD(produk_objek, 2, 0),LPAD(produk_rincian_objek, 2, 0),LPAD(produk_sub_rincian_objek, 2, 0),LPAD(produk_sub_sub_rincian_objek, 3, 0),LPAD(produk_kode, 3, 0)) as kode_produk")
        ->selectRaw("CONCAT_WS('.',belanja_akun,belanja_kelompok,belanja_jenis,LPAD(belanja_objek, 2, 0),LPAD(belanja_rincian_objek, 2, 0),LPAD(belanja_sub_rincian_objek, 2, 0),LPAD(belanja_kode, 3, 0)) as kode_belanja")
        ->where('periode', 'like', '%' .$select_periode . '%')
        ->where('produk_akun', $select_sub_rincian_objek[0])
        ->where('produk_kelompok', $select_sub_rincian_objek[1])
        ->where('produk_jenis', $select_sub_rincian_objek[2])
        ->where('produk_objek', $select_sub_rincian_objek[3])
        ->where('produk_rincian_objek', $select_sub_rincian_objek[4])
        ->where('produk_sub_rincian_objek', $select_sub_rincian_objek[5])
        ->whereNotNull('produk_sub_sub_rincian_objek')
        ->whereNull('produk_kode')
        ->get();

        return response()->json($response); 
    }

    public function getData(Request $request)
    {
        ## Read value
        $select_periode = $request->get('select_periode');
        $kode_sub_sub_rincian_objek = explode('.', $request->get('kode_sub_sub_rincian_objek'));

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Ssh::select('count(*) as allcount')->count();

        if(count($kode_sub_sub_rincian_objek) > 1) {
            $totalRecordswithFilter = Ssh::select('count(*) as allcount')
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->where('produk_akun', array_key_exists(0 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[0] : '')
                ->where('produk_kelompok', array_key_exists(1 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[1] : '')
                ->where('produk_jenis', array_key_exists(2 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[2] : '')
                ->where('produk_objek', array_key_exists(3 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[3] : '')
                ->where('produk_rincian_objek', array_key_exists(4 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[4] : '')
                ->where('produk_sub_rincian_objek', array_key_exists(5 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[5] : '')
                ->where('produk_sub_sub_rincian_objek', array_key_exists(6 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[6] : '')
                ->whereNotNull('produk_kode')
                ->where(function ($query) use($searchValue) {
                    $query->where('nama', 'like', '%' .$searchValue . '%')
                        ->orWhere('spesifikasi', 'like', '%' .$searchValue . '%')
                        ->orWhere('satuan', 'like', '%' .$searchValue . '%');
                })
                ->count();

            $records = Ssh::orderBy($columnName,$columnSortOrder)
                ->select("*")
                // ->selectRaw("LPAD(produk_objek, 2, 0) as produk_objek")
                // ->selectRaw("LPAD(produk_rincian_objek, 2, 0) as produk_rincian_objek")
                // ->selectRaw("LPAD(produk_sub_rincian_objek, 2, 0) as produk_sub_rincian_objek")
                // ->selectRaw("LPAD(produk_sub_sub_rincian_objek, 3, 0) as produk_sub_sub_rincian_objek")
                // ->selectRaw("LPAD(produk_kode, 3, 0) as produk_kode")
                // ->selectRaw("LPAD(belanja_objek, 2, 0) as belanja_objek")
                // ->selectRaw("LPAD(belanja_rincian_objek, 2, 0) as belanja_rincian_objek")
                // ->selectRaw("LPAD(belanja_sub_rincian_objek, 2, 0) as belanja_sub_rincian_objek")
                // ->selectRaw("LPAD(belanja_kode, 3, 0) as belanja_kode")
                ->selectRaw("CONCAT_WS('.',produk_akun,produk_kelompok,produk_jenis,LPAD(produk_objek, 2, 0),LPAD(produk_rincian_objek, 2, 0),LPAD(produk_sub_rincian_objek, 2, 0),LPAD(produk_sub_sub_rincian_objek, 3, 0),LPAD(produk_kode, 3, 0)) as kode_produk")
                ->selectRaw("CONCAT_WS('.',belanja_akun,belanja_kelompok,belanja_jenis,LPAD(belanja_objek, 2, 0),LPAD(belanja_rincian_objek, 2, 0),LPAD(belanja_sub_rincian_objek, 2, 0),LPAD(belanja_kode, 3, 0)) as kode_belanja")
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->where('produk_akun', array_key_exists(0 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[0] : '')
                ->where('produk_kelompok', array_key_exists(1 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[1] : '')
                ->where('produk_jenis', array_key_exists(2 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[2] : '')
                ->where('produk_objek', array_key_exists(3 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[3] : '')
                ->where('produk_rincian_objek', array_key_exists(4 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[4] : '')
                ->where('produk_sub_rincian_objek', array_key_exists(5 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[5] : '')
                ->where('produk_sub_sub_rincian_objek', array_key_exists(6 ,$kode_sub_sub_rincian_objek) ? $kode_sub_sub_rincian_objek[6] : '')
                ->whereNotNull('produk_kode')
                ->where(function ($query) use($searchValue) {
                    $query->where('nama', 'like', '%' .$searchValue . '%')
                        ->orWhere('spesifikasi', 'like', '%' .$searchValue . '%')
                        ->orWhere('satuan', 'like', '%' .$searchValue . '%');
                })
                ->skip($start)
                ->take($rowperpage)
                ->get();
        } else {
            $totalRecordswithFilter = Ssh::select('count(*) as allcount')
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->where(function ($query) use($searchValue) {
                    $query->where('nama', 'like', '%' .$searchValue . '%')
                        ->orWhere('spesifikasi', 'like', '%' .$searchValue . '%')
                        ->orWhere('satuan', 'like', '%' .$searchValue . '%');
                })
                ->count();

            $records = Ssh::orderBy($columnName,$columnSortOrder)
                ->select("*")
                // ->selectRaw("LPAD(produk_objek, 2, 0) as produk_objek")
                // ->selectRaw("LPAD(produk_rincian_objek, 2, 0) as produk_rincian_objek")
                // ->selectRaw("LPAD(produk_sub_rincian_objek, 2, 0) as produk_sub_rincian_objek")
                // ->selectRaw("LPAD(produk_sub_sub_rincian_objek, 3, 0) as produk_sub_sub_rincian_objek")
                // ->selectRaw("LPAD(produk_kode, 3, 0) as produk_kode")
                // ->selectRaw("LPAD(belanja_objek, 2, 0) as belanja_objek")
                // ->selectRaw("LPAD(belanja_rincian_objek, 2, 0) as belanja_rincian_objek")
                // ->selectRaw("LPAD(belanja_sub_rincian_objek, 2, 0) as belanja_sub_rincian_objek")
                // ->selectRaw("LPAD(belanja_kode, 3, 0) as belanja_kode")
                ->selectRaw("CONCAT_WS('.',produk_akun,produk_kelompok,produk_jenis,LPAD(produk_objek, 2, 0),LPAD(produk_rincian_objek, 2, 0),LPAD(produk_sub_rincian_objek, 2, 0),LPAD(produk_sub_sub_rincian_objek, 3, 0),LPAD(produk_kode, 3, 0)) as kode_produk")
                ->selectRaw("CONCAT_WS('.',belanja_akun,belanja_kelompok,belanja_jenis,LPAD(belanja_objek, 2, 0),LPAD(belanja_rincian_objek, 2, 0),LPAD(belanja_sub_rincian_objek, 2, 0),LPAD(belanja_kode, 3, 0)) as kode_belanja")
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->where(function ($query) use($searchValue) {
                    $query->where('nama', 'like', '%' .$searchValue . '%')
                        ->orWhere('spesifikasi', 'like', '%' .$searchValue . '%')
                        ->orWhere('satuan', 'like', '%' .$searchValue . '%');
                })
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $kode_produk = $record->kode_produk;
            $kode_belanja = $record->kode_belanja;
            $nama = $record->nama;
            $spesifikasi = $record->spesifikasi;
            $satuan = $record->satuan;
            $harga = $record->harga;
            $periode = $record->periode;
            // $produk_akun = $record->produk_akun;
            // $produk_kelompok = $record->produk_kelompok;
            // $produk_jenis = $record->produk_jenis;
            // $produk_objek = $record->produk_objek;
            // $produk_rincian_objek = $record->produk_rincian_objek;
            // $produk_sub_rincian_objek = $record->produk_sub_rincian_objek;
            // $produk_sub_sub_rincian_objek = $record->produk_sub_sub_rincian_objek;
            // $produk_kode = $record->produk_kode;
            // $belanja_akun = $record->belanja_akun;
            // $belanja_kelompok = $record->belanja_kelompok;
            // $belanja_jenis = $record->belanja_jenis;
            // $belanja_objek = $record->belanja_objek;
            // $belanja_rincian_objek = $record->belanja_rincian_objek;
            // $belanja_sub_rincian_objek = $record->belanja_sub_rincian_objek;
            // $belanja_kode = $record->belanja_kode;

            $data_arr[] = array(
                'id' => $id,
                'kode_produk' => $kode_produk,
                'kode_belanja' => $kode_belanja,
                'nama' => $nama,
                'spesifikasi' => $spesifikasi,
                'satuan' => $satuan,
                'harga' => $harga,
                'periode' => $periode,
                // 'produk' => array(
                //     'produk_akun' => $produk_akun,
                //     'produk_kelompok' => $produk_kelompok,
                //     'produk_jenis' => $produk_jenis,
                //     'produk_objek' => $produk_objek,
                //     'produk_rincian_objek' => $produk_rincian_objek,
                //     'produk_sub_rincian_objek' => $produk_sub_rincian_objek,
                //     'produk_sub_sub_rincian_objek' => $produk_sub_sub_rincian_objek,
                //     'produk_kode' => $produk_kode,
                // ),
                // 'belanja' => array(
                //     'belanja_akun' => $belanja_akun,
                //     'belanja_kelompok' => $belanja_kelompok,
                //     'belanja_jenis' => $belanja_jenis,
                //     'belanja_objek' => $belanja_objek,
                //     'belanja_rincian_objek' => $belanja_rincian_objek,
                //     'belanja_sub_rincian_objek' => $belanja_sub_rincian_objek,
                //     'belanja_kode' => $belanja_kode,
                // )
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response); 
    }

}
