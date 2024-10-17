<form action="/karyawan/{{ $karyawan->nik }}/update" method="POST" id="formKaryawan" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input type="text" value="{{ $karyawan->nik }}" id="nik" class="form-control" name="nik" placeholder="Nik"/>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input type="text" value="{{ $karyawan->nama_lengkap }}" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Karyawan"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input type="text" value="{{ $karyawan->jabatan }}" id="jabatan" class="form-control" name="jabatan" placeholder="Jabatan"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input type="text" value="{{ $karyawan->no_hp }}" id="no_hp" class="form-control" name="no_hp" placeholder="No. HP"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input class="form-control" name="foto" type="file" id="formFile">
                <input type="hidden" name="old_foto" value="{{ $karyawan->foto }}">
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <select name="kode_dept" id="kode_dept" class="form-select">
                    <option value="">Departemen</option>
                    @foreach ($departemen as $d )
                        <option {{ $karyawan->kode_dept == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                    @endforeach
                </select>
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