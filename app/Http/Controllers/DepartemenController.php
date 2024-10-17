<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DepartemenController extends Controller
{
    public function index(Request $request){

        $searchTerm = $request->input('search');

        $query = Departemen::query();
        $query->select('*');
       
        //query untuk mencari nama_dept dan kode_dept sekaligus
        if(!empty($searchTerm)){
            $query->where('nama_dept', 'like', '%'. $searchTerm. '%')
            ->orWhere('kode_dept', 'like', '%'. $searchTerm. '%');
        }
        $departemen = $query->get();
        // $departemen = DB::table('departemen')->orderBy('kode_dept')->get();
        return view('departemen.index', compact('departemen'));
    }

    public function store(Request $request){

        $kode_dept = $request->kode_dept;
        $nama_dept = $request->nama_dept;
        $data = [
            'kode_dept' => $kode_dept,
            'nama_dept' => $nama_dept
        ];

        $simpan = DB::table('departemen')->insert($data);
        if($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil di Simpan']);
        }else{
            return Redirect::back()->with(['error' => 'Data Gagal di Simpan']);
        }
    }

    public function edit(Request $request){

        $kode_dept = $request->kode_dept;
        $departemen = DB::table('departemen')->where('kode_dept', $kode_dept)->first();
        return view('departemen.edit', compact('departemen'));
    }

    public function update($kode_dept, Request $request){

        $kode_dept_baru = $request->kode_dept;
        $nama_dept = $request->nama_dept;
       
        $data = [
            'kode_dept' => $kode_dept_baru,
            'nama_dept' => $nama_dept
        ];

        $update = DB::table('departemen')->where('kode_dept', $kode_dept)->update($data);
        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        }else{
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);
        }
    }

    public function delete($kode_dept){

        $hapus = DB::table('departemen')->where('kode_dept',$kode_dept)->delete();
        if($hapus){
            return Redirect::back()->with(['success' => 'Data Berhasil di Hapus']);
        }else{
            return Redirect::back()->with(['error' => 'Data Gagal di Hapus']);
        }
    }
}
