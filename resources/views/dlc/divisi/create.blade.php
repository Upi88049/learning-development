@extends('layouts.admindlc')

@section('title', 'Tambah Divisi | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Master Data</p>
                    <h1 class="h3 mb-1">Tambah Divisi Baru</h1>
                </div>
            </div>
            <div class="heading-actions">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('divisi.index') }}">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Divisi
                </a>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="row g-3">
            <div class="col-12 col-xl-6">
                <form action="{{ route('divisi.store') }}" method="POST" class="panel p-4">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-card-heading me-2" aria-hidden="true"></i><span>Informasi Divisi</span></h2>
                            <p class="text-muted mb-0">Masukkan nama divisi baru yang akan didaftarkan.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="nama_divisi">Nama Divisi <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_divisi" name="nama_divisi" type="text" value="{{ old('nama_divisi') }}" placeholder="Contoh: IT, HRD, Production, Finance" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a class="btn btn-outline-secondary" href="{{ route('divisi.index') }}">Batal</a>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Divisi
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>

@endsection
