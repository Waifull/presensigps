@extends('layouts.admin.main')
@section('content')
<style>
     .page-body {
       margin-left: 300px;

    }

    .profile{
        width: 50px;
        height: 30px;

       border-radius: 3px;
    }

    .search-button {
        display: flex;
        align-items: center; 
    }

    .add-data{
        display: inline-flex;
        align-items: center;
    
    }

    

</style>
<div class="page-header d-print-none" style="margin-top: 70px; margin-left: 300px">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Departemen
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button href="" class="btn btn-primary add-data" id="tambahDepartemen" data-bs-toggle="modal" data-bs-target="#modal-inputkaryawan">
                                    <ion-icon name="add-outline" style="font-size: 20px;margin-right: 4px;"></ion-icon>
                                    Tambah Data
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/departemen" method="GET">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="form-group">
                                                <input type="text" name="search" id="search" class="form-control"
                                                 placeholder="Departemen" value="{{ Request('nama_dept') }}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary search-button">
                                                    <ion-icon name="search-outline" style="margin-right: 4px"></ion-icon>Search</button>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Departemen</th>
                                            <th>Departemen</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($departemen as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->nama_dept }}</td>
                                            <td style="display: flex;">
                                                <a href="#" class="edit btn-info btn-sm me-2" kode_dept="{{ $d->kode_dept }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a href="">
                                                
                                                <form action="/departemen/{{ $d->kode_dept }}/delete" method="POST">
                                                @csrf
                                                <a class="btn btn-danger btn-sm delete-confirm">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                                </form>
                                            </td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      
                       
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>

<div class="modal fade" id="modal-inputdepartemen" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Departemen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/departemen/store" method="POST" id="formDepartemen">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="input-icon mb-3">
                        <input type="text" value="" id="kode_dept" class="form-control" name="kode_dept" placeholder="Kode Departemen"/>
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-12">
                    <div class="input-icon mb-3">
                        <input type="text" value="" id="nama_dept" class="form-control" name="nama_dept" placeholder="Nama Departemen"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </div>
            </div>
          </form>
        </div>
       
      </div>
    </div>
</div>


{{-- Modal Edit --}}
<div class="modal fade" id="modal-editdepartemen" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Departemen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">
            
        </div>
       
      </div>
    </div>
  </div>



  
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#tambahDepartemen").click(function() {
                $("#modal-inputdepartemen").modal("show");
            });

            $(".edit").click(function() {
                var kode_dept = $(this).attr('kode_dept');
                $.ajax({
                    type: 'POST',
                    url: 'departemen/edit',
                    cache: false,
                    data:{
                        _token: "{{ csrf_token() }}",
                        kode_dept: kode_dept,
                    },
                    success:function(respond){
                        $("#loadeditform").html(respond);
                    }
                });
                $("#modal-editdepartemen").modal("show");
            });

            $(".delete-confirm").click(function(e) {
                let form = $(this).closest('form')
                e.preventDefault();
                Swal.fire({
                    title: 'Anda yakin untuk hapus data ini?',
                    text: 'Jika iya maka data akan terhapus permanen',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus saja!',
                }).then((result) => {
                    if (result.isConfirmed){
                        form.submit();
                        Swal.fire('Deleted!',
                        'Data Berhasil di Hapus',
                        'success')
                    }
                })
            })

            $("#formKaryawan").submit(function() {
                let nik = $("#nik").val();
                let nama_lengkap = $("#nama_lengkap").val();
                let jabatan = $("#jabatan").val();
                let no_hp = $("#no_hp").val();
                let kode_dept = $("formkaryawan").find("#kode_dept").val();
                if(nik == ""){
                   Swal.fire({
                        title: "Warning!",
                        text: 'Nik harus diisi!',
                        icon: 'warning',
                        confirmButtonText: "Ok"
                   }).then((result) => {
                        $("#nik").focus();
                   });

                   return false;
                }else if(nama_lengkap == ""){
                    Swal.fire({
                        title: "Warning!",
                        text: 'Nama harus diisi!',
                        icon: 'warning',
                        confirmButtonText: "Ok"
                   }).then((result) => {
                        $("#nama_lengkap").focus();
                   });

                   return false;
                }else if(jabatan == ""){
                    Swal.fire({
                        title: "Warning!",
                        text: 'Jabatan harus diisi!',
                        icon: 'warning',
                        confirmButtonText: "Ok"
                   }).then((result) => {
                        $("#jabatan").focus();
                   });

                   return false;
                }else if(no_hp == ""){
                    Swal.fire({
                        title: "Warning!",
                        text: 'No. HP harus diisi!',
                        icon: 'warning',
                        confirmButtonText: "Ok"
                   }).then((result) => {
                        $("#no_hp").focus();
                   });

                   return false;
                }else if(kode_dept == ""){
                    Swal.fire({
                        title: "Warning!",
                        text: 'Departemen harus diisi!',
                        icon: 'warning',
                        confirmButtonText: "Ok"
                   }).then((result) => {
                        $("#kode_dept").focus();
                   });

                   return false;
                }
            });
        });

    </script>
@endpush