@extends('layouts.admindlc')

@section('title', 'Edit Provider | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Edit Data</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Edit Provider</h1>
                        <p class="text-muted mb-0 small">Perbarui data informasi lembaga atau vendor penyelenggara pelatihan.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('provider.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Provider
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('provider.update', $provider->id_provider) }}" method="POST" class="panel p-4">
            @csrf
            @method('PUT')
            <div class="panel-header border-bottom pb-3 mb-3">
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-card-text me-2 text-primary" aria-hidden="true"></i>
                    <span>Edit Informasi Provider</span>
                </h2>
                <p class="text-muted mb-0 small">Kode Provider: <strong>{{ $provider->provider_code }}</strong></p>
            </div>

            <div class="row g-3">
                {{-- Provider Code --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="provider_code">
                        Provider Code <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control font-monospace @error('provider_code') is-invalid @enderror"
                           id="provider_code"
                           name="provider_code"
                           value="{{ old('provider_code', $provider->provider_code) }}"
                           required>
                    <div class="form-text small">Kode unik penyedia pelatihan.</div>
                    @error('provider_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Provider Name --}}
                <div class="col-12 col-md-8">
                    <label class="form-label fw-semibold" for="provider_name">
                        Provider Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('provider_name') is-invalid @enderror"
                           id="provider_name"
                           name="provider_name"
                           value="{{ old('provider_name', $provider->provider_name) }}"
                           required>
                    @error('provider_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Provider Type --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="provider_type">
                        Provider Type <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('provider_type') is-invalid @enderror" id="provider_type" name="provider_type" required>
                        @php
                            $types = ['External', 'Internal', 'Konsultan', 'Lembaga Sertifikasi', 'Universitas / Akademisi', 'Lainnya'];
                            $selectedType = old('provider_type', $provider->provider_type);
                        @endphp
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ $selectedType == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('provider_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PIC --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="pic">
                        Person In Charge (PIC)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                        <input type="text"
                               class="form-control @error('pic') is-invalid @enderror"
                               id="pic"
                               name="pic"
                               value="{{ old('pic', $provider->pic) }}"
                               placeholder="Nama kontak penanggung jawab">
                    </div>
                    @error('pic')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="phone">
                        Phone / WhatsApp
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-telephone text-muted"></i></span>
                        <input type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $provider->phone) }}"
                               placeholder="Contoh: 021-89830001">
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold" for="email">
                        Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $provider->email) }}"
                               placeholder="Contoh: info@provider.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold" for="address">
                        Alamat / Lokasi Kantor
                    </label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address"
                              name="address"
                              rows="2">{{ old('address', $provider->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Note --}}
                <div class="col-12">
                    <label class="form-label fw-semibold" for="note">
                        Catatan Tambahan
                    </label>
                    <textarea class="form-control @error('note') is-invalid @enderror"
                              id="note"
                              name="note"
                              rows="2">{{ old('note', $provider->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('provider.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i> Perbarui Provider
                </button>
            </div>
        </form>
    </div>
</main>

@endsection
