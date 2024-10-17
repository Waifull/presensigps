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
                    Konfigurasi Lokasi
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
                        @if (Session::get('success'))
                        <div class="alert alert-success"> 
                            {{ Session::get('success') }}
                        </div>
                        @endif

                        @if (Session::get('warning'))
                        <div class="alert alert-danger">
                            {{ Session::get('warning') }}
                        </div>
                        @endif
                        <form action="/konfigurasi/updatelokasikantor" method="POST">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" value="{{ $lokasiKantor->lokasi_kantor }}" id="lokasi_kantor" class="form-control" placeholder="Lokasi Kantor" name="lokasi_kantor">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" value="{{ $lokasiKantor->radius }}" id="radius" class="form-control" placeholder="Radius" name="radius">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 mt-3">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        Update
                                    </button>
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