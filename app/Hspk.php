<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hspk extends Model
{
    use HasFactory;

    protected $table = "hspks";

    protected $fillable = [
      'peraturan_id',
      'col_a',
      'col_b',
      'col_c',
      'col_d',
      'col_e',
      'col_f',
      'col_g',
      'col_h',
      'kode_turunan',
      'periode'
    ];
}
