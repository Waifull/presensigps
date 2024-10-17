<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request){


        //join table karyawan dengan department
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept' );
        $query->orderBy('nama_lengkap');
        
        //search nama
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap', 'like', '%' .$request->nama_karyawan . '%');
        }

        //search departemen
        if(!empty($request->kode_dept)){
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        $karyawan = $query->paginate(10);

       
        

        //menampilkan departemen
        $departemen = DB::table('departemen')->get();
        return view('karyawan.index', compact('karyawan', 'departemen'));
    }

    public function store(Request $request){

        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('12345');
        if ($request->hasFile('foto')){
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = null;
        }

        try{
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                if($request->hasFile('foto')){
                    $folderPath = "public/uploads/profilekaryawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => "Data Berhasil Disimpan"]);
            }
        } catch (\Exception $e){
            
            return Redirect::back()->with(['warning' => "Data Gagal Disimpan"]);
        }

    }

    public function edit(Request $request){

        $nik = $request->nik;
        $departemen = DB::table('departemen')->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.editKaryawan', compact('departemen', 'karyawan'));
    }

    public function update($nik, Request $request){

        $nik_baru = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('12345');
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')){
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $old_foto;
        }

        try{
            $data = [
                'nik' => $nik_baru,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);
            if ($update) {
                if($request->hasFile('foto')){

                    $folderPath = "public/uploads/profilekaryawan/";
                    $folderPathOld = "public/uploads/profilekaryawan/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => "Data Berhasil di Update"]);
            }
        } catch (\Exception $e){
            
            return Redirect::back()->with(['warning' => "Data Gagal di Update"]);
        }
    }

    public function delete($nik){

        $delete = DB::table('karyawan')->where('nik', $nik)
        ->delete();
        if($delete){
            return Redirect::back()->with(['success' => "Data Berhasil di Hapus"]);
        }else{
            return Redirect::back()->with(['warning' => "Data Gagal di Hapus"]);
        }
    }
}