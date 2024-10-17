@extends('layouts.admin.main')
@section('content')
<style>
    .page-body {
       margin-left: 300px;

    }
    .card {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 16px;
        width: 200px;
    }
    .icon {
     
        color: white;
        border-radius: 4px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
    }
    .icon i {
        font-size: 24px;
    }
    .text {
        display: flex;
        flex-direction: column;
    }
    .text .sales {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 4px;
    }
    .text .payments {
        font-size: 14px;
        color: #888;
    }
</style>
<div class="page-header d-print-none" style="margin-top: 70px; margin-left: 300px">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Dashboard
                </h2>
            </div>
        </div>
    </div>

</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row text-center">


            <div class="card" style="margin-right: 45px">
                <div class="icon bg-success">
                    <ion-icon name="finger-print-outline"></ion-icon>
                </div>
                <div class="text">
                   
                    <div class="sales">
                        {{ $rekapPresensi->jumlahHadir > 0 ?  $rekapPresensi->jumlahHadir : 0}}
                    </div>
                    <div class="payments">Karyawan Hadir</div>
                </div>
            </div>

            <div class="card" style="margin-right: 45px">
                <div class="icon bg-info">
                    <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                </div>
                <div class="text">
                   
                    <div class="sales">
                        {{ $rekapIzin->jumlahIzin > 0 ? $rekapIzin->jumlahIzin : 0 }}
                    </div>
                    <div class="payments">Karyawan Izin</div>
                </div>
            </div>

            
            <div class="card" style="margin-right: 45px">
                <div class="icon bg-warning">
                    <ion-icon name="medkit-outline"></ion-icon>
                </div>
                <div class="text">
                   
                    <div class="sales">
                        {{ $rekapIzin->jumlahSakit > 0 ? $rekapIzin->jumlahSakit : 0 }}
                    </div>
                    <div class="payments">Karyawan Sakit</div>
                </div>
            </div>

            <div class="card">
                <div class="icon bg-danger">
                    <ion-icon name="alarm-outline"></ion-icon>
                </div>
                <div class="text">
                   
                    <div class="sales">
                        {{ $rekapPresensi->jumlahTelat > 0 ?  $rekapPresensi->jumlahTelat : 0}}
                    </div>
                    <div class="payments">Karyawan Telat</div>
                </div>
            </div>

        </div>
    </div>
    
</div>
@endsection