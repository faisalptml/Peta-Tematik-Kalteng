<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KabKota extends Model
{
    use HasFactory;
    protected $table = 'kab_kota';
    protected $fillable = ['name','alt_name','latitude','longitude','provinsi_id'];

    function kab_kota(){
        return $this->belongsTo(KabKota::class);
    }
}
