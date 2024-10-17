<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $hariIni = date("Y-m-d");
        $bulanIni = date("m") * 1;
        $tahunIni = date("Y");
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensiHariIni = DB::table('presensi')->where('nik', $nik)
        ->where('tgl_presensi', $hariIni)
        ->first();
        $historiBulanIni = DB::table('presensi')->where('nik', $nik)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanIni . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunIni . '"')
        ->orderBy('tgl_presensi')->get();


        //query rekap absensi **07.00 untuk setting waktu masuk 
        $rekapPresensi = DB::table('presensi')->selectRaw('COUNT(nik) as jumlahHadir,
        SUM(IF(jam_in > "07:00",1,0)) as jumlahTelat')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanIni . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunIni . '"')
        ->first();

        //join untuk meengambil melalui data nik karena terhubung
        //di tabel karyawan
        $leaderboard = DB::table('presensi')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->where('tgl_presensi', $hariIni)
        ->orderBy('jam_in')
        ->get();

        $namaBulan = [
            "", "Januari", 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $rekapIzin = DB::table('pengajuan_izin')->selectRaw('SUM(IF(status="i",1,0)) as jumlahIzin,
        SUM(IF(status="s",1,0)) as jumlahSakit')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_izin)="' . $bulanIni . '"')
        ->whereRaw('YEAR(tgl_izin)="' . $tahunIni . '"')
        ->where('status_approved', 1)
        ->first();


    return view('layouts.dashboard', compact('presensiHariIni', 'historiBulanIni',
     'namaBulan', 'tahunIni', 'bulanIni', 'rekapPresensi', 'leaderboard', 'rekapIzin'));
    }


    public function dashboardAdmin(){

        $hariIni = date("Y-m-d");
        //query rekap absensi **07.00 untuk setting waktu masuk 
        $rekapPresensi = DB::table('presensi')->selectRaw('COUNT(nik) as jumlahHadir,
        SUM(IF(jam_in > "07:00",1,0)) as jumlahTelat')
        ->where('tgl_presensi', $hariIni)
        ->first();

        $rekapIzin = DB::table('pengajuan_izin')->selectRaw('SUM(IF(status="i",1,0)) as jumlahIzin,
        SUM(IF(status="s",1,0)) as jumlahSakit')
        ->where('tgl_izin', $hariIni)
        ->where('status_approved', 1)
        ->first();

        return view('layouts.dashboardAdmin', compact('rekapPresensi', 'rekapIzin'));
    }

    
}
