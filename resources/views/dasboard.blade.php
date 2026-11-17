
@extends('layout.app')

@section('isi')

<style>
    body {
        background-color: #f5f7f6;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
    }

    .stat-icon {
        font-size: 28px;
        margin-bottom: 10px;
    }
</style>

<div class="container py-4">

    <!-- BAGIAN 1: HEADER DASHBOARD -->
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard ApotekCare</h3>
        <p class="text-muted">
            Selamat datang di sistem manajemen ApotekCare.
        </p>
    </div>


    <!-- BAGIAN 2: KARTU STATISTIK -->
    <div class="row g-3 mb-4">

        <!-- Total Obat -->
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <i class="bi bi-capsule text-success stat-icon"></i>
                <p class="text-muted mb-1">Total Jenis Obat</p>
                <h3 class="fw-bold">100</h3>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <i class="bi bi-box-seam text-primary stat-icon"></i>
                <p class="text-muted mb-1">Total Stok Valid</p>
                <h3 class="fw-bold">1.200</h3>
            </div>
        </div>

        <!-- Kedaluwarsa -->
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <i class="bi bi-exclamation-triangle text-warning stat-icon"></i>
                <p class="text-muted mb-1">Segera Kedaluwarsa</p>
                <h3 class="fw-bold">5 Batch</h3>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <i class="bi bi-cash-stack text-danger stat-icon"></i>
                <p class="text-muted mb-1">Pendapatan Hari Ini</p>
                <h3 class="fw-bold">Rp 1.500.000</h3>
            </div>
        </div>

    </div>


    <!-- BAGIAN 3: AREA GRAFIK -->
    <div class="row g-3">

        <div class="col-md-8">
            <div class="card stat-card shadow-sm p-4 h-100">

                <h5 class="fw-bold mb-3">Grafik Penjualan</h5>

                <div class="bg-light rounded p-5 text-center">
                    <i class="bi bi-bar-chart-line fs-1 text-success"></i>
                    <p class="text-muted mt-2 mb-0">
                        Grafik penjualan akan ditampilkan di sini.
                    </p>
                </div>

            </div>
        </div>


        <!-- BAGIAN 4: INFORMASI PENTING -->
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-4 h-100">

                <h5 class="fw-bold mb-3">Informasi Penting</h5>

                <div class="mb-3">
                    <i class="bi bi-exclamation-circle text-warning"></i>
                    <strong> Stok dan Kedaluwarsa</strong>
                    <p class="text-muted small">
                        Terdapat 5 batch yang perlu diperiksa.
                    </p>
                </div>

                <hr>

                <div class="mb-3">
                    <i class="bi bi-box-seam text-success"></i>
                    <strong> Manajemen Stok</strong>
                    <p class="text-muted small">
                        Pantau ketersediaan stok obat.
                    </p>
                </div>

                <hr>

                <div>
                    <i class="bi bi-graph-up text-primary"></i>
                    <strong> Laporan Penjualan</strong>
                    <p class="text-muted small">
                        Pantau perkembangan transaksi penjualan.
                    </p>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection
