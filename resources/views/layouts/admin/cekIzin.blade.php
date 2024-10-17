@extends('layouts.admin.main')
@section('content')
<style>
      .page-body {
       margin-left: 300px;
    }

    .search-button {
        display: flex;
        align-items: center; 
    }

   
</style>
<div class="page-header d-print-none" style="margin-top: 70px; margin-left: 300px">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Izin / Sakit
                </h2>
            </div>
        </div>
    </div>

</div>

<div class="page-body">
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
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <form action="/presensi/pengajuanizin" method="GET" autocomplete="off">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><ion-icon name="calendar-outline"></ion-icon></span>
                                <input type="text" value="{{ Request('dari') }}" id="dari" class="form-control" name="dari" placeholder="Dari"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><ion-icon name="calendar-outline"></ion-icon></span>
                                <input type="text" value="{{ Request('sampai') }}" id="sampai" class="form-control" name="sampai" placeholder="Sampai"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><ion-icon name="barcode-outline"></ion-icon></span>
                                <input type="text" value="{{ Request('nik') }}" id="nik" class="form-control" name="nik" placeholder="Nik"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" value="{{ Request('nama_lengkap') }}" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Karyawan"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <select name="status_approved" id="status_approved" class="form-select">
                                    <option value="">Pilih Status</option>
                                    <option value="0" {{ Request('status_approved') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status_approved') == 1 ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status_approved') == 2 ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <button class="btn btn-primary search-button" type="submit">
                                    <ion-icon name="search-outline" style="margin-right: 4px"></ion-icon>
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nik</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Status Approve</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuanIzin as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}</td>
                                <td>{{ $d->nik }}</td>
                                <td>{{ $d->nama_lengkap }}</td>
                                <td>{{ $d->jabatan }}</td>
                                <td>{{ $d->status == "i" ? "Izin" : "Sakit" }}</td>
                                <td>{{ $d->keterangan }}</td>
                                <td>
                                    @if ($d->status_approved == 1)
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif ($d->status_approved == 2)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($d->status_approved == 0)
                                        <a href="#" class="btn btn-sm btn-primary" id="approve" id_pengajuanizin="{{ $d->id }}">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                            Approve
                                        </a>
                                    @else
                                        <a href="/pengajuanizin/{{ $d->id }}/cancel" class="btn bg-danger btn-sm">
                                            <i class="bi bi-x-circle"></i>
                                            Cancel
                                        </a>
                                    @endif
                                  
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                {{ $pengajuanIzin->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-pengajuanizin" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Izin / Sakit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/pengajuanizin/approve" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuanizin_form" id="id_pengajuanizin_form">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <select name="status_approved" id="status_approve" class="form-select">
                                <option value="1">Setuju</option>
                                <option value="2">Tolak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-group">
                           <button class="btn btn-primary w-100" type="submit">
                            <i class="bi bi-send"></i>
                            Submit
                           </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
       
      </div>
    </div>
  </div>
@endsection

@push('myscript')
  <script>
    $(function(){
        $('#approve').click(function(e){
            e.preventDefault();
            var id = $(this).attr('id_pengajuanizin');
            $("#id_pengajuanizin_form").val(id);
            $('#modal-pengajuanizin').modal('show');
        });

        $("#dari, #sampai").datepicker({ 
                    autoclose: true, 
                    todayHighlight: true,
                    format: 'yyyy-mm-dd'
            });
    });
  </script>
@endpush