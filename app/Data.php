<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'tb_data_covid';
    protected $fillable = ['id_data', 'id_kab', 'tanggal', 'rawat', 'sembuh', 'meninggal', 'positif'];
    public $timestamps = false;
}
