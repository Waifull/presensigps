@extends('layouts.admin.main')
@section('content')
<style>
    .page-body{
        margin-left: 300px;
    }
</style>
<div class="page-header d-print-none" style="margin-top: 70px; margin-left: 300px">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Chart
                </h2>
            </div>
        </div>
    </div>

</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <canvas id="attendanceChart" width="500" height="450"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>

  
    var data = @json($data);
    // Konfigurasi Chart.js
    var ctx = document.getElementById('attendanceChart').getContext('2d');
    var attendanceChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Izin', 'Sakit', 'Hadir', 'Telat'], // Menambahkan label 'Telat'
            datasets: [{
                data: [ 
                    data.izin, 
                    data.sakit, 
                    data.hadir, 
                    data.telat
                    // '50',
                    // '200',
                    // '200',
                    // '500'
                ],
                backgroundColor: [
                    'rgba(255, 159, 64, 0.6)', // Warna untuk izin
                    'rgba(255, 99, 132, 0.6)', // Warna untuk sakit
                    'rgba(75, 192, 192, 0.6)', // Warna untuk hadir
                    'rgba(255, 206, 86, 0.6)'  // Warna untuk telat
                ],
                borderColor: [
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)', // Memperbaiki kesalahan penulisan
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Menjaga rasio aspek agar tetap lingkaran
            plugins: {
                legend: {
                    position: 'top'
                },
                title:{
                    display: true,
                    text: 'Data Chart Bulan Oktober'
        }
            },
          
    }
       
    });
</script>
@endpush