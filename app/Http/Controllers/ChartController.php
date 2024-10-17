<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    
    public function index(){

        $hariIni = date("Y-m-d");
        $rekapPresensi = DB::table('presensi')->selectRaw('COUNT(nik) as jumlahHadir,
        SUM(IF(jam_in > "07:00",1,0)) as jumlahTelat')
        ->where('tgl_presensi', $hariIni)
        ->first();

        $rekapIzin = DB::table('pengajuan_izin')->selectRaw('SUM(IF(status="i",1,0)) as jumlahIzin,
        SUM(IF(status="s",1,0)) as jumlahSakit')
        ->where('tgl_izin', $hariIni)
        ->where('status_approved', 1)
        ->first();

        $data = [
            'izin' => $rekapIzin->jumlahIzin,
            'sakit' => $rekapIzin->jumlahSakit,
            'hadir' => $rekapPresensi->jumlahHadir,
            'telat' => $rekapPresensi->jumlahTelat
        ];

        return view('laporan.chart', compact( 'data'));
    }
}
