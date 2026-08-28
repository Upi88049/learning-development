@extends('layouts.admindlc')

@section('title', 'Tambah Department | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Master Data</p>
                    <h1 class="h3 mb-1">Tambah Department Baru</h1>
                </div>
            </div>
            <div class="heading-actions">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('department.index') }}">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Department
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
                <form action="{{ route('department.store') }}" method="POST" class="panel p-4">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-card-heading me-2" aria-hidden="true"></i><span>Informasi Department</span></h2>
                            <p class="text-muted mb-0">Pilih divisi dan masukkan nama department baru.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="id_divisi">Divisi <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_divisi" name="id_divisi" required>
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisi as $div)
                                    <option value="{{ $div->id_divisi }}" {{ old('id_divisi') == $div->id_divisi ? 'selected' : '' }}>
                                        {{ $div->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="nama_department">Nama Department <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_department" name="nama_department" type="text" value="{{ old('nama_department') }}" placeholder="Contoh: Learning & Development, GA & IR" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a class="btn btn-outline-secondary" href="{{ route('department.index') }}">Batal</a>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Department
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>

@endsection
