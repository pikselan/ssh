<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Hspk;
use App\Peraturan;

class ViewHspkController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->hasRole('superadmin')) {
            $admin = 'superadmin';
        } elseif ($request->user()->hasRole('admin')) {
            $admin = 'admin';
        } else {
            $admin = 'user';
        }

        $req_select_periode = $request->get('select_periode');
        if(isset($req_select_periode)) {
            if(empty($req_select_periode)) {
                $arr_select_periode = Hspk::select('id','periode')->groupBy('periode')->orderBy('id', 'DESC')->first();
                $select_periode = $arr_select_periode->periode;
            } else {
                $select_periode = $req_select_periode;
            }
        } else {
            $arr_select_periode = Hspk::select('id','periode')->groupBy('periode')->orderBy('id', 'DESC')->first();
            // $arr_select_periode = DB::select('select id, periode from hspks group by periode order by id desc limit 1', [1]);
            $select_periode = $arr_select_periode->periode;
        };
        
        $list_periode = Hspk::select('periode')->groupBy('periode')->get();

        $data_peraturan = Peraturan::orderBy('id', 'DESC')->first();

        return view('view-hspk.index',
        [
            'admin'=>$admin,
            'list_periode'=>$list_periode, 
            'select_periode'=>$select_periode,
            'data_peraturan'=>$data_peraturan
        ]);
    }
    
    public function getData(Request $request)
    {
        ## Read value
        $select_periode = $request->get('select_periode');
        
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
        $totalRecords = Hspk::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Hspk::select('count(*) as allcount')
            ->where('periode', 'like', '%' .$select_periode . '%')
            ->where('col_b', 'like', '%' .$searchValue . '%')
            ->whereNull('kode_turunan')
            ->count();

        if($rowperpage == -1) {
            $records = Hspk::orderBy($columnName,$columnSortOrder)
                ->select("*")
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->where('col_b', 'like', '%' .$searchValue . '%')
                ->whereNull('kode_turunan')
                ->get();
        } else {
            $records = Hspk::orderBy($columnName,$columnSortOrder)
                ->select("*")
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->where('col_b', 'like', '%' .$searchValue . '%')
                ->whereNull('kode_turunan')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }
        
        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $col_a = $record->col_a;
            $col_b = $record->col_b;
            $periode = $record->periode;

            $sub_turunan = Hspk::where('col_a', $record->col_a)
                ->where('periode', 'like', '%' .$select_periode . '%')
                ->whereNotNull('kode_turunan')
                ->get();

            $data_arr[] = array(
                'id' => $id,
                'col_a' => $col_a,
                'col_b' => $col_b,
                'periode' => $periode,
                'sub_turunan' => $sub_turunan,
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
