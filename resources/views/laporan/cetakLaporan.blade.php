<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Presensi</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
  @page { size: A4 }

    #title{
        font-family: Arial, Arial, Helvetica, sans-serif;
        font-size: 18px;
        font-weight: bold;
        
    }

    .tabeldatakaryawan{
        margin-top: 40px;

    }

    .tabeldatakaryawan tr td{
        padding: 5px;
    }

    .tabelpresensi{
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .tabelpresensi tr th{
        border: 1px solid #0c0c0c;
        padding: 8px;
        background-color: rgba(186, 188, 186, 0.663)

    }

    .tabelpresensi tr td{
        border: 1px solid #0c0c0c;
        padding: 5px;
        font-size: 12px;

    }

    .foto{
        width: 40px;
        height: 30px;

    }
  </style>
</head>
<?php
// functions.php

if (!function_exists('selisih')) {
    function selisih($jam_masuk, $jam_keluar) {
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
}
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
    
   
  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ asset('assets/img/logoIndev.png') }}" width="70" height="70" alt="">
            </td>
            <td>
                <span id="title">
                    LAPORAN PRESENSI KARYAWAN<br>
                    PERIODE {{ strtoupper($namaBulan[$bulan]) }} {{ $tahun }}<br>
                    PT. INDEV<br>
                </span>
                <span><i>Jl. Sidosermo PDK II No. 228, Kecamatan Wonocolo, Kota Surabaya</i></span>
            </td>
        </tr>
    </table>
    <table class="tabeldatakaryawan">
        <tr>
            <td rowspan="6">
                @php
                    $path = Storage::url('uploads/profilekaryawan/'.$karyawan->foto);
                @endphp
                 @if (empty($d->foto))
                 <img src="{{ asset("assets/img/nophoto.png") }}" class="profile" alt="">
                 @else
                 <img src="{{ url($path) }}" class="profile" alt="">
                 @endif
            </td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>:</td>
            <td>{{ $karyawan->nama_dept }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>{{ $karyawan->no_hp }}</td>
        </tr>
    </table>
    <table class="tabelpresensi">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Foto</th>
            <th>Jam Pulang</th>
            <th>Foto</th>
            <th>Keterangan</th>
            <th>Jumlah Jam</th>
        </tr>
        @foreach ($presensi as $d )
        @php
        // require 'functions.php'; // Memasukkan fungsi dari functions.php
        $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
        $path_out = Storage::url('uploads/absensi/'.$d->foto_out);
        $jamTerlambat = selisih('07:00:00', $d->jam_in)
           
        @endphp
            <tr style="text-align: center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
                <td>{{ $d->jam_in }}</td>
                <td><img src="{{ url($path_in) }}" alt="" class="foto"></td>
                <td>{{ $d->jam_out != null ? $d->jam_out : "Belum Absen" }}</td>
                <td>
                    @if ($d->jam_out != null)
                    <img src="{{ url($path_out) }}" alt="" class="foto"></td>
                    @else
                    <img src="{{ asset('assets/img/nophoto.png') }}" alt="">
                    @endif
                <td>
                    @if ($d->jam_in > '07:00')
                        Terlambat {{ $jamTerlambat }}
                    @else
                        Tepat Waktu
                    @endif  
                </td>
                <td>
                    @if ($d->jam_out != null)
                        @php
                            // require 'functions.php'; // Memasukkan fungsi dari functions.php
                            $jmlJamKerja = selisih($d->jam_in, $d->jam_out)
                        @endphp
                    @else
                        @php
                            $jmlJamKerja = 0;
                        @endphp
                    @endif
                    {{ $jmlJamKerja }}
                </td>
            </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top: 100px">
        <tr>
            <td style="text-align: right;" colspan="2">Surabaya, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: bottom" height="150x">
                <u>Zulfikar Azmi</u><br>
                <i><b>HRD Manager</b></i>
            </td>
            <td style="text-align: center; vertical-align: bottom">
                <u>Budi Setiawan</u><br>
                <i><b>Direktur</b></i>
            </td>
        </tr>
    </table>
  </section>

</body>

</html>