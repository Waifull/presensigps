<?php

namespace App\Http\Controllers;

use App\Models\pengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hariIni = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariIni)->where('nik', $nik)->count();
        $lokasiKantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        return view('presensi.create', compact('cek', 'lokasiKantor'));
    }

    public function store(Request $request)
    {   
       

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i");
        $lokasiKantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lokasiKantor->lokasi_kantor);
        $latitudeKantor = trim($lok[0]);
        $longitudeKantor = trim($lok[1]);
        $lokasi = $request->lokasi;
        $lokasiUser = explode(",", $lokasi);
        $latitudeUser = $lokasiUser[0];
        $longitudeUser = $lokasiUser[1];

        $jarak = $this->distance($latitudeKantor, $longitudeKantor, $latitudeUser, $longitudeUser);
        $radius = round($jarak ["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if ($cek > 0){
            $ket = 'out';
        }else{
            $ket = 'in';
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName =  $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;
       

        // //radiius cobacoba
        // if($radius > 50){
        //     echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda ". $radius . " Meter dari Kantor|radius";
        // }else{
        
        //radius kantor
        if($radius > $lokasiKantor->radius){
                echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda ". $radius . " Meter dari Kantor|radius";
        }else{

      
        if($cek > 0){
            $data_pulang = [
               
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'lokasi_out' => $lokasi
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);

            
                if($update){
                    echo 'success|Terimakasih! Hati-hati di jalan.|out';
                    Storage::put($file, $image_base64);
                }else{
                    echo 'error|Anda Gagal Absen|out'; 
                }
        }else{
            $data = [
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi
            ];
            //memasukkan ke database
            $simpan = DB::table('presensi')->insert($data);
    
            if($simpan){
                echo 'success|Terimakasih. Selamat beraktivitas!|in';
                Storage::put($file, $image_base64);
            }else{
                echo 'error|Anda Gagal Absen|in'; 
            }
        }

     }



        
        

    }

    // Function Untuk Menghitung Jarak Antara 2 Titik Koordinat
    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editProfile(){
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editProfile', compact('karyawan'));
    }

    public function updateProfile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        if ($request->hasFile('foto')){
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $karyawan->foto;
        }

        if(empty($request->password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        }else{
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }
       
        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update){
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/profilekaryawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Profil Berhasil di Update']);
        }else{
            return Redirect::back()->with(['error' => 'Profil Gagal di Update']);
        }
        
    }

    public function history()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei",
        "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('presensi.history', compact('namaBulan'));
    }

    public function getHistory(Request $request){

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $history = DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="' . $bulan .'"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahun .'"')->where('nik', $nik)
        ->orderBy('tgl_presensi')
        ->get();

        return view('presensi.getHistory', compact('history'));
    }

    public function izin(){

        $nik = Auth::guard('karyawan')->user()->nik;
        $dataIzin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataIzin'));
    }

    public function buatIzin(){
        return view('presensi.buatIzin');
    }

    public function simpanIzin(Request $request){

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan,
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan){
            return redirect('/presensi/izin')->with(['success' => 'Berhasil Membuat Izin']);
        }else{
            return redirect('/presensi/izin')->with(['error' => 'Gagal Membuat Izin']);
        }
    }

    public function monitoring(){

        return view('layouts.admin.monitoring');
    }

    public function getPresensi(Request $request){

        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
        ->select('presensi.*', 'nama_lengkap', 'nama_dept')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
        ->where('tgl_presensi', $tanggal)
        ->get();

        return view('layouts.admin.getPresensi', compact('presensi'));

    }

    public function showMap(Request $request){

        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->first();
        return view('layouts.admin.showMap', compact('presensi'));
    }

    public function laporan(){

        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei",
        "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();

        return view('laporan.laporanPresensi', compact('namaBulan', 'karyawan'));
    }

    public function cetakLaporan(Request $request){

        $nik = $request->nik;
        $bulan =$request->bulan;
        $tahun = $request->tahun;

        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei",
        "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $karyawan = DB::table('karyawan')->where('nik', $nik)
        ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
        ->first();

        $presensi = DB::table('presensi')->where('nik', $nik)->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->orderBy('tgl_presensi')
        ->get();
        return view('laporan.cetakLaporan', compact('bulan', 'tahun', 'namaBulan', 'karyawan', 'presensi'));
    }

    public function rekap(){

        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei",
        "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

       

        return view('laporan.rekap', compact('namaBulan'));
    }

    public function cetakRekap(Request $request){

       
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    
    $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei",
    "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    $rekap = DB::table('presensi')
        ->selectRaw('presensi.nik, nama_lengkap,
            MAX(IF(DAY(tgl_presensi) = 1, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_1,
            MAX(IF(DAY(tgl_presensi) = 2, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_2,
            MAX(IF(DAY(tgl_presensi) = 3, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_3,
            MAX(IF(DAY(tgl_presensi) = 4, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_4,
            MAX(IF(DAY(tgl_presensi) = 5, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_5,
            MAX(IF(DAY(tgl_presensi) = 6, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_6,
            MAX(IF(DAY(tgl_presensi) = 7, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_7,
            MAX(IF(DAY(tgl_presensi) = 8, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_8,
            MAX(IF(DAY(tgl_presensi) = 9, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_9,
            MAX(IF(DAY(tgl_presensi) = 10, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_10,
            MAX(IF(DAY(tgl_presensi) = 11, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_11,
            MAX(IF(DAY(tgl_presensi) = 12, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_12,
            MAX(IF(DAY(tgl_presensi) = 13, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_13,
            MAX(IF(DAY(tgl_presensi) = 14, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_14,
            MAX(IF(DAY(tgl_presensi) = 15, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_15,
            MAX(IF(DAY(tgl_presensi) = 16, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_16,
            MAX(IF(DAY(tgl_presensi) = 17, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_17,
            MAX(IF(DAY(tgl_presensi) = 18, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_18,
            MAX(IF(DAY(tgl_presensi) = 19, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_19,
            MAX(IF(DAY(tgl_presensi) = 20, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_20,
            MAX(IF(DAY(tgl_presensi) = 21, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_21,
            MAX(IF(DAY(tgl_presensi) = 22, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_22,
            MAX(IF(DAY(tgl_presensi) = 23, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_23,
            MAX(IF(DAY(tgl_presensi) = 24, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_24,
            MAX(IF(DAY(tgl_presensi) = 25, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_25,
            MAX(IF(DAY(tgl_presensi) = 26, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_26,
            MAX(IF(DAY(tgl_presensi) = 27, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_27,
            MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_28,
            MAX(IF(DAY(tgl_presensi) = 29, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_29,
            MAX(IF(DAY(tgl_presensi) = 30, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_30,
            MAX(IF(DAY(tgl_presensi) = 31, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_31')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
        ->groupBy('presensi.nik', 'nama_lengkap')
        ->get();

        return view('laporan.cetakRekap', compact('bulan', 'tahun', 'rekap', 'namaBulan'));
    }

    public function cekIzin(Request $request){

        $query = pengajuanIzin::query();    
        $query->select('id', 'tgl_izin', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');

        //search dari dan sampai
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        //search nik
        if (!empty($request->nik)){
            $query->where('pengajuan_izin.nik', $request->nik);
        }

         //search nama karyawan
         if (!empty($request->nama_lengkap)){
            $query->where('nama_lengkap', 'like', '%'. $request->nama_lengkap . '%');
        }

         //search status approve
         if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2'){
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin', 'desc');
        $pengajuanIzin = $query->paginate(20);
        $pengajuanIzin->appends($request->all());
        return view('layouts.admin.cekIzin', compact('pengajuanIzin'));
    }

    public function approveIzin(Request $request){

        $status = $request->status_approved;
        $id = $request->id_pengajuanizin_form;

        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => $status
        ]);

        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal di Update']);
        }
    }   

    public function cancelIzin($id){

        
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => 0
        ]);

        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal di Update']);
        }
        
    }

    public function validasiIzin(Request $request){

        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();

        Return $cek;
    }
}
