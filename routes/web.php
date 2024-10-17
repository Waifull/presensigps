<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:karyawan'])->group(function (){
    
    Route::get('/', function () {
    return view('auth.login');
    })->name('login');

    Route::post('/proseslogin', [AuthController::class, 'prosesLogin']);

});


Route::middleware(['guest:user'])->group(function (){
    
    Route::get('/panel', function () {
    return view('auth.loginAdmin');
    })->name('loginAdmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesLoginAdmin']);
});

Route::middleware(['auth:karyawan'])->group(function (){
    Route::get('proseslogout', [AuthController::class, 'prosesLogout']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //presensi
    Route::get('/presensi/create', [PresensiController::class, 'create' ]);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile  
    Route::get('/editprofile', [PresensiController::class, 'editProfile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateProfile']);

    //History
    Route::get('/presensi/history', [PresensiController::class, 'history']);
    Route::post('/gethistory', [PresensiController::class, 'getHistory']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatIzin']);
    Route::post('/presensi/simpanizin', [PresensiController::class, 'simpanIzin']);
    //validasi izin agar tidak bisa izin di hari yang sama
    Route::post('/presensi/validasiizin', [PresensiController::class, 'validasiIzin']);
    
});

Route::middleware(['auth:user'])->group(function() {
    //Admin
    Route::get('proseslogoutadmin', [AuthController::class, 'prosesLogoutAdmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardAdmin']);

    //Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);
    
    //Departemen
    Route::get('/departemen', [DepartemenController::class, 'index']);
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    //Presensi Monitor
    Route::get('/admin/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getPresensi']);
    Route::post('/showmap', [PresensiController::class, 'showMap']);

    //laporan presensi
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetakLaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakRekap']);

    //konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasiKantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updateLokasiKantor']);

    //pengajuan izin
    Route::get('/presensi/pengajuanizin', [PresensiController::class, 'cekIzin']);
    Route::post('/pengajuanizin/approve', [PresensiController::class, 'approveIzin']);
    Route::get('/pengajuanizin/{id}/cancel', [PresensiController::class, 'cancelIzin']);

   //Chart
   Route::get('/presensi/chart', [ChartController::class, 'index']);
});

