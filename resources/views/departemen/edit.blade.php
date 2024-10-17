<form action="/departemen/{{ $departemen->kode_dept }}/update" method="POST" id="formDepartemen">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input type="text" value="{{ $departemen->kode_dept }}" id="kode_dept" class="form-control" name="kode_dept" placeholder="Kode Departemen"/>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <input type="text" value="{{ $departemen->nama_dept }}" id="nama_dept" class="form-control" name="nama_dept" placeholder="Nama Departemen"/>
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