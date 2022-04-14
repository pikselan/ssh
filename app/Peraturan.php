<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Peraturan extends Model
{
    protected $table = "peraturans";

    protected $fillable = [
      'nama',
      'file'
  ];
    
}
