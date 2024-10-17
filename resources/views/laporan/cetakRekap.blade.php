<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Rekap Presensi</title>

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
        background-color: rgba(186, 188, 186, 0.663);
        font-size: 11px;

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

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4 landscape">

    

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
                    REKAP PRESENSI KARYAWAN<br>
                    PERIODE {{ strtoupper($namaBulan[$bulan]) }} {{ $tahun }}<br>
                    PT. INDEV<br>
                </span>
                <span><i>Jl. Sidosermo PDK II No. 228, Kecamatan Wonocolo, Kota Surabaya</i></span>
            </td>
        </tr>
    </table>
   
    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">Nik</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">Total Hadir</th>
            <th rowspan="2">Total Telat</th>
        </tr>
        <tr>
            <?php
            for($i = 1; $i <= 31; $i++){
            ?>
            <th>{{ $i }}</th>
            <?php
            }
            ?>
         
        </tr>
        @foreach ($rekap as $d)
        <tr>
            <td>{{ $d->nik }}</td>
            <td>{{ $d->nama_lengkap }}</td>

            <?php
            $totalHadir = 0;
            $totalTelat = 0;
            for($i = 1; $i <= 31; $i++){
                $tgl = "tgl_" . $i;

               
                if(empty($d->$tgl)){
                    $hadir = ['', ''];
                    $totalHadir += 0;
                }else{
                    $hadir = explode("-",$d->$tgl);
                    $totalHadir += 1;
                    if($hadir[0] > "07:00:00"){
                        $totalTelat += 1;
                    }
                }
            ?>
            <td>
                <span style="color: {{ $hadir[0] > "07:00:00" ? "red" : "" }}">{{ $hadir[0] }}</span><br>

                <span style="color: {{ $hadir[1] < "16:00:00" ? "red" : "" }}">{{ $hadir[1] }}</span><br>
                

            </td>
            <?php
            }
            ?>
            <td>{{ $totalHadir }}</td>
            <td>{{ $totalTelat }}</td>

        </tr>
        @endforeach
    </table>
   

    <table width="100%" style="margin-top: 100px">
        <tr>
            <td></td>
            <td style="text-align: center;">Surabaya, {{ date('d-m-Y') }}</td>
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