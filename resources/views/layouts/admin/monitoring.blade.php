@extends('layouts.admin.main')
@section('content')
<style>
      .page-body {
       margin-left: 300px;
    }

   
</style>
<div class="page-header d-print-none" style="margin-top: 70px; margin-left: 300px">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Monitoring Presensi
                </h2>
            </div>
        </div>
    </div>

</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body mt-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" value="{{ date("Y-m-d") }}" id="tanggal" name="tanggal" placeholder="Tanggal Presensi" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nik</th>
                                            <th>Nama Karyawan</th>
                                            <th>Departemen</th>
                                            <th>Jam Masuk</th>
                                            <th>Foto</th>
                                            <th>Jam Pulang</th>
                                            <th>Foto</th>
                                            <th>Keterangan</th>
                                            <th>Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadpresensi"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>

<div class="modal fade" id="modal-showmap" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Lokasi Presensi User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadmap">
            
        </div>
       
      </div>
    </div>
  </div>
@endsection

@push('myscript')
    <script>
        $(function () {
            $("#tanggal").datepicker({ 
                    autoclose: true, 
                    todayHighlight: true,
                    format: 'yyyy-mm-dd'
            });

            function loadpresensi(){
                var tanggal = $("#tanggal").val();
                $.ajax({
                    type: "POST",
                    url: '/getpresensi',
                    data:{
                        _token:"{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success:function(respond){
                        $("#loadpresensi").html(respond);
                    }
                });
            }
            $("#tanggal").change(function(e){
                loadpresensi()
            });

            loadpresensi()
        });

    </script>
@endpush