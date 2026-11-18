@extends('layout.app')

@section('title', 'Dashboard - ApotekCare')

@section('isi')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #ecfdf5; color: #10b981; font-size: 1.3rem;">
            <i class="bi bi-grid"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Dashboard ApotekCare</h4>
            <p class="text-muted small mb-0">Pantau stok, expired, dan prioritas obat dalam satu tampilan.</p>
        </div>
    </div>

    <!-- 4 Kartu Statistik (Sesuai Prototype PDF) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-clean p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #ecfdf5; color: #10b981; font-size: 1.3rem;">
                    <i class="bi bi-capsule"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Total Obat</small>
                    <h4 class="fw-bold mb-0 text-success">{{ $totalObat ?? 125 }} <span class="fs-6 fw-normal text-muted">Jenis</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-clean p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fffbeb; color: #f59e0b; font-size: 1.3rem;">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Stok Menipis</small>
                    <h4 class="fw-bold mb-0 text-warning">{{ $stokMenipis ?? 12 }} <span class="fs-6 fw-normal text-muted">Obat</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-clean p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #eff6ff; color: #3b82f6; font-size: 1.3rem;">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Mendekati Expired</small>
                    <h4 class="fw-bold mb-0 text-primary">{{ $mendekatiExpired ?? 8 }} <span class="fs-6 fw-normal text-muted">Obat</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-clean p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fef2f2; color: #ef4444; font-size: 1.3rem;">
                    <i class="bi bi-trash3"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Expired</small>
                    <h4 class="fw-bold mb-0 text-danger">{{ $sudahExpired ?? 3 }} <span class="fs-6 fw-normal text-muted">Obat</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Ringkasan Stok (Sesuai Prototype PDF) -->
    <div class="card-clean p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Stok & Monitoring Kedaluwarsa</h6>
            <a href="{{ url('/mengelola') }}" class="btn btn-sm btn-outline-success">Lihat Semua Obat</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Obat</th>
                        <th>Stok</th>
                        <th>Expired</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-medium">Paracetamol</td>
                        <td>5</td>
                        <td>20/06/2026</td>
                        <td><span class="badge bg-warning text-dark">Stok Menipis</span></td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Amoxicillin</td>
                        <td>10</td>
                        <td>15/06/2026</td>
                        <td><span class="badge bg-info text-dark">Mendekati Expired</span></td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Obat Batuk</td>
                        <td>0</td>
                        <td>10/06/2025</td>
                        <td><span class="badge bg-danger">Expired / Habis</span></td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Vitamin C</td>
                        <td>6</td>
                        <td>30/06/2026</td>
                        <td><span class="badge bg-success">Aman</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection