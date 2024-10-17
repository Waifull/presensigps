@extends('layouts.admin.main')
@section('content')
<style>
     .page-body {
       margin-left: 300px;

    }
    .cetak{
        display: flex; /* Flexbox to align icon and text */
        align-items: center; /* Align vertically */
        justify-content: center; /* Align horizontally */
        
    }

</style>
<div class="page-header d-print-none" style="margin-top: 70px; margin-left: 300px">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Laporan Presensi
                </h2>
            </div>
        </div>
    </div>

</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form action="/presensi/cetaklaporan" target="_blank" method="POST">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="bulan" id="bulan" class="form-select">
                                            @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>{{ $namaBulan[$i] }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="tahun" id="tahun" class="form-select">
                                            @php
                                            $tahunMulai = 2020;
                                            $tahunSekarang = date("Y");
                
                                            @endphp
                                            @for ($tahun = $tahunMulai; $tahun <= $tahunSekarang; $tahun++)
                                            <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="nik" id="nik" class="form-select">
                                            <option value="">Karyawan</option>
                                            @foreach ($karyawan as $d)
                                            <option value="{{ $d->nik }}">{{ $d->nama_lengkap }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <div class="form-group">
                                       <button type="submit" name="submit" class="btn btn-primary w-100 cetak">
                                        <ion-icon name="print-outline" class="me-1"></ion-icon>
                                        Cetak
                                       </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                       <button type="submit" name="export" class="btn btn-success w-100 export">
                                        <i class="bi bi-download" class="me-1"></i>
                                            Export to Excel
                                       </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection