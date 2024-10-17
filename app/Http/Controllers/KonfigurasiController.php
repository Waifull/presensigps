<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasiKantor(){ 

        $lokasiKantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        
        return view('konfigurasi.lokasiKantor', compact('lokasiKantor'));
    }

    public function updateLokasiKantor(Request $request){

        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id',1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if($update){
            return Redirect::back()->with(['success' => 'Lokasi Berhasil di Update']);
        }else{
            return Redirect::back()->with(['warning' => 'Lokasi Gagal di Update']);
        }
    }
}