<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class provinsi extends Model
{
    use HasFactory;
    protected $table = 'provinsi';
    protected $fillable = ['name','alt_name','latitude','longitude','provinsi_id'];

    function Provinsi(){
        return $this->belongsTo(provinsi::class);
    }
}
