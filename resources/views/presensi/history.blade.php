@extends('layouts.main')
@section('header')
<!--- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/dashboard" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle" style="font-size: 22px">History</div>
    <div class="right"></div>
</div>
<!-- App Header --->
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>{{ $namaBulan[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
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
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    
                    <button class="btn btn-primary btn-block" id="getData">
                        <ion-icon name="search-outline"></ion-icon>Search</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col" id="showHistory"></div>
</div>
@endsection


@push('myscript')
<script>
    //ketika button di klik menangkap id getData
    $(function(){
        $("#getData").click(function(){
            //menangkap value id bulan dan tahun
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();
            $.ajax({
                type: "POST",
                url: "/gethistory",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success:function(respond){
                    $("#showHistory").html(respond)
                }
            })
        })
    })
</script>
@endpush