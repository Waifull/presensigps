@extends('layouts.main')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal{
        max-height: 430px !important;
    }

    .datepicker-date-display{
        background-color: #0f3a7e !important;
    }
    .datepicker-table td.is-selected{
        background-color: #0f3a7e !important;
    }

    .datepicker-cancel, .datepicker-clear, .datepicker-today, .datepicker-done{
        color: #0f3a7e !important;
    }

   
    
</style>
<!--- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/dashboard" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle" style="font-size: 21px">Form Izin</div>
    <div class="right"></div>
</div>
<!-- App Header --->
@endsection

@section('content')
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <form action="/presensi/simpanizin" method="POST" id="form_izin">
        @csrf
           
        <div class="form-group">
            <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" placeholder="Tanggal">
        </div>
             
           
        <div class="form-group">
            <select name="status" id="status" class="form-control">
                <option value="">Izin / Sakit</option>
                <option value="i">Izin</option>
                <option value="s">Sakit</option>
            </select>
        </div>
          
         
        <div class="form-group">
            <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary w-100">Submit</button>
        </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

$(document).ready(function() {
    $(".datepicker").datepicker({

        format: "yyyy-mm-dd"    
    });

    $("#tgl_izin").change(function(e){

        var tgl_izin = $(this).val();

        $.ajax({
            type: 'POST',
            url: '/presensi/validasiizin',
            data:{
                _token: "{{ csrf_token() }}",
                tgl_izin: tgl_izin
            },
            cache: false,
            success:function(respond){
                if (respond == 1){
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Anda sudah Izin hari ini',
                        icon: 'warning'
                    }).then((result) => {
                        $("#tgl_izin").val("");
                    });
                }
            }

        });
    })

    $("#form_izin").submit(function() {
        let tgl_izin = $('#tgl_izin').val();
        let status = $('#status').val();
        let keterangan = $('#keterangan').val();

        if(tgl_izin == ''){
            Swal.fire({
                title: 'Oops!',
                text: 'Tanggal harus di isi',
                icon: 'warning',
            
                });
            return false
        }else if(status == ''){
            Swal.fire({
                title: 'Oops!',
                text: 'Status harus di isi',
                icon: 'warning',
            
                });
        }else if(keterangan == ''){
            Swal.fire({
                title: 'Oops!',
                text: 'Keterangan harus di isi',
                icon: 'warning',
            
                });
        }
    })
});

</script>
@endpush