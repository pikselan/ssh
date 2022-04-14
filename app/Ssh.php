<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ssh extends Model
{
  use HasFactory;

  protected $table = "sshes";

  protected $fillable = ['peraturan_id',
    'produk_akun',
    'produk_kelompok',
    'produk_jenis',
    'produk_objek',
    'produk_rincian_objek',
    'produk_sub_rincian_objek',
    'produk_sub_sub_rincian_objek',
    'produk_kode',
    'belanja_akun',
    'belanja_kelompok',
    'belanja_jenis',
    'belanja_objek',
    'belanja_rincian_objek',
    'belanja_sub_rincian_objek',
    'belanja_kode',
    'nama',
    'spesifikasi',
    'satuan',
    'harga',
    'zona',
    'jenis_data',
    'periode'
];
  
  // public function hspk()
  // {
  //     return $this->hasMany('App\Hspk');
  // }
  
  public function scopeDataPeriodeTerbaru($query)
  {
      // return $query->whereIn('periode', array(DB::raw('SELECT max(id),periode FROM sshes b GROUP BY b.periode ORDER BY b.id DESC LIMIT 1')));
      // return $query->whereIn('periode', array(DB::raw('SELECT periode FROM sshes b GROUP BY b.periode ORDER BY b.id desc LIMIT 1')));
      // $value = DB::raw('SELECT periode FROM sshes b GROUP BY b.periode ORDER BY b.id desc LIMIT 1');
      // return $query->where('periode', $value);
  }
    
}
