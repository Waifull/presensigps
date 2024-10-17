@php
        // Function Untuk Menghitung Selisih Jam
        function selisih($jam_masuk, $jam_keluar){
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
@endphp

@foreach ($presensi as $d )
@php
    $foto_in = Storage::url('uploads/absensi/'. $d->foto_in);
    $foto_out = Storage::url('uploads/absensi/'. $d->foto_out);
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_lengkap }}</td>
        <td>{{ $d->nama_dept }}</td>
        <td>{{ $d->jam_in != null ? $d->jam_in : "Belum Absen" }}</td>
        <td>
            <img src="{{ url($foto_in) }}" style="width: 50px;height: 30px;" alt="">
        </td>
        <td>{{ $d->jam_out != null ? $d->jam_out : "Belum Absen" }}</td>
        <td>
            @if ($d->jam_out != null)
            <img src="{{ url($foto_out) }}" style="width: 50px;height: 30px;" alt="">
            @else
            <i class="bi bi-hourglass-split"></i>
            @endif
        </td>
        <td>
            @if ($d->jam_in > '07:00')
            @php
                $jamTerlambat = selisih('07:00:00', $d->jam_in)
            @endphp
                <span class="badge bg-danger">Terlambat {{ $jamTerlambat }}</span>
            @else
                <span class="badge bg-success">Tepat Waktu</span>
            @endif
        </td>
        <td>
            <a href="#" class="btn btn-primary showmap" id="{{ $d->id }}">
                <i class="bi bi-geo-alt-fill"></i>
            </a>
        </td>
    </tr>
@endforeach

<script>
    $(function(){
        $(".showmap").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type: "POST",
                url: '/showmap',
                data:{
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success:function(respond){
                    $("#loadmap").html(respond);
                }
            })
            $("#modal-showmap").modal("show");
        });
    });
</script>