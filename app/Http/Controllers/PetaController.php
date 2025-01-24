<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\KabKota;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        return view('kalteng');
    }

    public function Pduktiv()
    {
        return view('produktivitas');
    }

    public function Lpanen()
    {
        return view('luasPanen');
    }

    public function Padi()
    {
        return view('padi');
    }

    public function Beras()
    {
        return view('beras');
    }

    public function statistik()
    {
        return view('Analisis');
    }

    public function Interaktif()
    {
        return view('interaktif');
    }
}