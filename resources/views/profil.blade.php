@extends('layout.app')
@section('title', 'Profil Akun - ApotekCare')
@section('isi')
<div class="container-fluid" style="max-width: 600px;">
    <h4 class="fw-bold mb-4">Profil Akun</h4>
    <div class="card-clean p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div>
                <h5 class="fw-bold mb-0">{{ Auth::user()->name ?? 'Admin ApotekCare' }}</h5>
                <small class="text-muted">{{ Auth::user()->email ?? 'admin@apotekcare.test' }}</small>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label small text-muted">Nama Lengkap</label>
            <input type="text" class="form-control" value="{{ Auth::user()->name ?? 'Admin ApotekCare' }}" readonly>
        </div>
        <div class="mb-0">
            <label class="form-label small text-muted">Email</label>
            <input type="email" class="form-control" value="{{ Auth::user()->email ?? 'admin@apotekcare.test' }}" readonly>
        </div>
    </div>
</div>
@endsection