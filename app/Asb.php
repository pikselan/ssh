<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asb extends Model
{
  use HasFactory;

  protected $table = "asbs";

  protected $fillable = [
    'peraturan_id',
    'kode',
    'kode_turunan',
    'nama',
    'nilai',
    'periode'
  ];
}
