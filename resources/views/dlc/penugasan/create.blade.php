@extends('layouts.admindlc')

@section('content')
<style>
/* ========== SMOOTH GLOBAL ENHANCEMENTS ========== */
.admin-content {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Page Hero Header */
.form-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    margin-bottom: 1.5rem;
}

/* Step Section Panels */
.step-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.2s ease;
}

.step-panel-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 1rem;
    margin-bottom: 1.25rem;
    border-bottom: 1.5px solid #f1f5f9;
}

.step-circle {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

/* Smooth Form Controls */
.form-label-smooth {
    font-size: 0.825rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.form-control-smooth {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 9px 13px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background-color: #ffffff;
}

.form-control-smooth:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    background-color: #ffffff;
    outline: none;
}

.form-control-smooth:disabled,
.form-control-smooth[readonly] {
    background-color: #f8fafc;
    border-color: #e2e8f0;
    color: #475569;
}

/* Table Peserta Smooth */
.table-peserta-container {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
}

.table-peserta {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table-peserta thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 10px 12px;
    border-bottom: 1.5px solid #e2e8f0;
    border-top: none;
}

.table-peserta tbody td {
    padding: 8px 10px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-peserta tbody tr:last-child td {
    border-bottom: none;
}

/* Total Biaya Card */
.total-biaya-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1.5px solid #bbf7d0;
    border-radius: 12px;
    padding: 1rem 1.25rem;
}

/* Sticky Action Bar */
.bottom-action-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08);
    margin-top: 2rem;
    margin-bottom: 2rem;
}

/* Gradients for Action Buttons */
.btn-gradient-blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff !important;
    border: none;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: all 0.2s ease;
}
.btn-gradient-blue:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-1.5px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

.btn-gradient-green {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff !important;
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    transition: all 0.2s ease;
}
.btn-gradient-green:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-1.5px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
}
</style>

<main class="admin-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Hero Header --}}
        <div class="form-hero-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 52px; height: 52px; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 font-monospace" style="font-size: 0.72rem;">
                                Form 013/WI-
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                Formulir Pendaftaran Training
                            </span>
                        </div>
                        <h1 class="h4 mb-1 text-dark fw-bold">Buat Formulir Pendaftaran &amp; Penugasan Training</h1>
                        <p class="text-muted mb-0 small">Isi data formulir penugasan training resmi Dharma Learning Center.</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('penugasan.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Form
                    </a>
                </div>
            </div>
        </div>

        {{-- Errors / Alerts --}}
        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show my-3 rounded-3 p-3 d-flex align-items-center gap-2" role="alert" style="background-color: #fef2f2; border-left: 4px solid #ef4444 !important;">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <span class="text-dark">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show my-3 rounded-3 p-3" role="alert" style="background-color: #fef2f2; border-left: 4px solid #ef4444 !important;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <strong class="text-danger">Terdapat kesalahan input:</strong>
            </div>
            <ul class="mb-0 ps-4 small text-dark">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($requestOuthouse)
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background-color: #eff6ff; border-left: 4px solid #3b82f6 !important;">
            <i class="bi bi-info-circle-fill fs-3 text-primary"></i>
            <div>
                <strong class="d-block text-dark">Terkait Request Out House: {{ $requestOuthouse->no_request }}</strong>
                <small class="text-muted">
                    Formulir ini dibuat otomatis berdasarkan permohonan staff <strong>{{ $requestOuthouse->staff ? $requestOuthouse->staff->nama_staff : '-' }}</strong> (NPK: {{ $requestOuthouse->staff ? $requestOuthouse->staff->npk_staff : '-' }}).
                </small>
            </div>
        </div>
        @endif

        {{-- Main Form --}}
        <form action="{{ route('penugasan.store') }}" method="POST" id="formPenugasan">
            @csrf
            <input type="hidden" name="id_request_outhouse" value="{{ old('id_request_outhouse', $defaultData['id_request_outhouse']) }}">

            {{-- 1. INFORMASI UMUM TRAINING --}}
            <div class="step-panel">
                <div class="step-panel-header">
                    <span class="step-circle">1</span>
                    <div>
                        <h2 class="h6 mb-0 fw-bold text-dark">Informasi Training &amp; Perusahaan</h2>
                        <small class="text-muted">Data judul pelatihan, kategori, serta unit perusahaan/divisi.</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_training" class="form-label-smooth">Nama Training <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-smooth" id="nama_training" name="nama_training" value="{{ old('nama_training', $defaultData['nama_training']) }}" placeholder="Contoh: Problem Solving (PDCA), ISO 9001:2015, dll." required>
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_training" class="form-label-smooth">Jenis Training <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-smooth" id="jenis_training" name="jenis_training" value="{{ old('jenis_training', $defaultData['jenis_training']) }}" placeholder="Contoh: Out House Training / Technical / Leadership" required>
                    </div>

                    <div class="col-md-5">
                        <label for="sub_co" class="form-label-smooth">SubCo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-smooth" id="sub_co" name="sub_co" value="{{ old('sub_co', $defaultData['sub_co']) }}" placeholder="Contoh: PT. Dharma Polimetal Tbk" required>
                    </div>

                    <div class="col-md-4">
                        <label for="divisi" class="form-label-smooth">Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-smooth" id="divisi" name="divisi" value="{{ old('divisi', $defaultData['divisi']) }}" placeholder="Contoh: Human Capital & General Affairs" required>
                    </div>

                    <div class="col-md-3">
                        <label for="no_form" class="form-label-smooth">No. Dokumen</label>
                        <input type="text" class="form-control form-control-smooth font-monospace" id="no_form" name="no_form" value="{{ old('no_form', $defaultData['no_form']) }}">
                    </div>
                </div>
            </div>

            {{-- 2. TABEL PESERTA TRAINING --}}
            <div class="step-panel">
                <div class="step-panel-header d-flex flex-wrap justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="step-circle">2</span>
                        <div>
                            <h2 class="h6 mb-0 fw-bold text-dark">Data Peserta Training</h2>
                            <small class="text-muted">Daftar nama karyawan yang ditugaskan mengikuti pelatihan.</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        {{-- Quick Picker from Staff Master --}}
                        <select class="form-select form-select-sm form-control-smooth" id="staffQuickPicker" style="max-width: 260px;">
                            <option value="">-- Tambah Cepat dari Staff --</option>
                            @foreach($allStaff as $st)
                            @php
                                $bagianSt = $st->department ? $st->department->nama_department : ($st->divisi ? $st->divisi->nama_divisi : '-');
                            @endphp
                            <option value="{{ $st->id_staff }}" 
                                data-npk="{{ $st->npk_staff }}" 
                                data-nama="{{ $st->nama_staff }}"
                                data-bagian="{{ $bagianSt }}"
                                data-jabatan="{{ $st->levelJabatan ? $st->levelJabatan->kode_level_jabatan : 'SF' }}"
                                data-atasan="{{ $st->immediateManager ? $st->immediateManager->nama_staff : '-' }}">
                                {{ $st->nama_staff }} ({{ $st->npk_staff }})
                            </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3 d-inline-flex align-items-center gap-1" id="btnAddParticipant">
                            <i class="bi bi-plus-lg"></i> Tambah Baris
                        </button>
                    </div>
                </div>

                <div class="table-peserta-container">
                    <div class="table-responsive">
                        <table class="table-peserta align-middle small" id="tablePeserta">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">No.</th>
                                    <th style="width: 130px;">NPK</th>
                                    <th>Nama Lengkap Peserta <span class="text-danger">*</span></th>
                                    <th style="width: 170px;">Bagian</th>
                                    <th style="width: 120px;">Jabatan</th>
                                    <th>Atasan Langsung</th>
                                    <th style="width: 90px;" class="text-center">Paraf</th>
                                    <th style="width: 50px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pesertaTableBody">
                                @php
                                    $pesertaRows = old('peserta', $defaultData['peserta']);
                                @endphp
                                @foreach($pesertaRows as $idx => $p)
                                <tr class="peserta-row" data-index="{{ $idx }}">
                                    <td class="text-center row-number fw-bold text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-smooth input-npk" name="peserta[{{ $idx }}][npk]" value="{{ $p['npk'] ?? '' }}" placeholder="NPK">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-smooth input-nama" name="peserta[{{ $idx }}][nama]" value="{{ $p['nama'] ?? '' }}" placeholder="Nama Lengkap" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-smooth input-bagian" name="peserta[{{ $idx }}][bagian]" value="{{ $p['bagian'] ?? '' }}" placeholder="Bagian / Dept">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-smooth input-jabatan" name="peserta[{{ $idx }}][jabatan]" value="{{ $p['jabatan'] ?? '' }}" placeholder="Jabatan">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-smooth input-atasan" name="peserta[{{ $idx }}][atasan]" value="{{ $p['atasan'] ?? '' }}" placeholder="Atasan Langsung">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-smooth text-center" name="peserta[{{ $idx }}][paraf]" value="{{ $p['paraf'] ?? '' }}" placeholder="-">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center btn-delete-row" style="width: 30px; height: 30px;" title="Hapus Baris">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-info-circle me-1"></i> Data bagian akan otomatis terisi nama Divisi jika karyawan tidak memiliki Department.
                </small>
            </div>

            {{-- 3. BIAYA INVESTASI & KALKULASI --}}
            <div class="step-panel">
                <div class="step-panel-header">
                    <span class="step-circle">3</span>
                    <div>
                        <h2 class="h6 mb-0 fw-bold text-dark">Biaya Investasi Pelatihan</h2>
                        <small class="text-muted">Kalkulasi total biaya investasi per peserta dan kalimat terbilang.</small>
                    </div>
                </div>

                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="jumlah_peserta" class="form-label-smooth">Jumlah Peserta</label>
                        <div class="input-group">
                            <input type="number" class="form-control form-control-smooth fw-bold" id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', count($pesertaRows)) }}" readonly>
                            <span class="input-group-text bg-light text-muted small">Orang</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="biaya_per_peserta" class="form-label-smooth">Biaya per Peserta (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="number" step="any" class="form-control form-control-smooth" id="biaya_per_peserta" name="biaya_per_peserta" value="{{ old('biaya_per_peserta', $defaultData['biaya_per_peserta']) }}" placeholder="0" required>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="total-biaya-card">
                            <span class="d-block small text-muted fw-semibold">Total Biaya Investasi:</span>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fs-5 text-success fw-bold">Rp</span>
                                <input type="text" class="form-control border-0 bg-transparent fs-4 fw-bold text-success p-0" id="total_biaya_display" value="0" readonly style="box-shadow: none;">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="terbilang" class="form-label-smooth">Terbilang</label>
                        <input type="text" class="form-control form-control-smooth font-monospace" id="terbilang" name="terbilang" value="{{ old('terbilang', $defaultData['terbilang']) }}" placeholder="Otomatis terisi kalimat terbilang...">
                        <small class="text-muted d-block mt-1"><em>*) Note : Biaya Ditanggung Perusahaan</em></small>
                    </div>
                </div>
            </div>

            {{-- 4. ALASAN MENGIKUTI PELATIHAN --}}
            <div class="step-panel">
                <div class="step-panel-header">
                    <span class="step-circle">4</span>
                    <div>
                        <h2 class="h6 mb-0 fw-bold text-dark">Alasan Mengikuti Pelatihan</h2>
                        <small class="text-muted">Uraian tujuan bisnis atau peningkatan kompetensi kerja.</small>
                    </div>
                </div>

                <div>
                    <label for="alasan_pelatihan" class="form-label-smooth">Uraian Alasan Pelatihan</label>
                    <textarea class="form-control form-control-smooth" id="alasan_pelatihan" name="alasan_pelatihan" rows="3" placeholder="Tuliskan tujuan, urgensi bisnis, atau kompetensi yang diharapkan...">{{ old('alasan_pelatihan', $defaultData['alasan_pelatihan']) }}</textarea>
                </div>
            </div>

            {{-- 5. DATA PERSETUJUAN DIVISI & PENYELENGGARAAN --}}
            <div class="step-panel">
                <div class="step-panel-header">
                    <span class="step-circle">5</span>
                    <div>
                        <h2 class="h6 mb-0 fw-bold text-dark">Data Persetujuan Divisi &amp; Pelaksanaan</h2>
                        <small class="text-muted">Jadwal pelaksanaan, tanda tangan persetujuan atasan/direktur, dan konfirmasi DLC.</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="nama_atasan" class="form-label-smooth">Nama Atasan (Div/Dept)</label>
                        <input type="text" class="form-control form-control-smooth" id="nama_atasan" name="nama_atasan" value="{{ old('nama_atasan', $defaultData['nama_atasan']) }}" placeholder="Nama Atasan Langsung">
                    </div>

                    <div class="col-md-4">
                        <label for="divisi_atasan" class="form-label-smooth">Divisi Atasan</label>
                        <input type="text" class="form-control form-control-smooth" id="divisi_atasan" name="divisi_atasan" value="{{ old('divisi_atasan', $defaultData['divisi_atasan']) }}" placeholder="Divisi Atasan">
                    </div>

                    <div class="col-md-4">
                        <label for="jabatan_atasan" class="form-label-smooth">Jabatan Atasan</label>
                        <input type="text" class="form-control form-control-smooth" id="jabatan_atasan" name="jabatan_atasan" value="{{ old('jabatan_atasan', $defaultData['jabatan_atasan']) }}" placeholder="Contoh: Department Head / Division Head">
                    </div>

                    <div class="col-12">
                        <label for="tempat_tanggal_training" class="form-label-smooth">Tempat &amp; Tanggal Penyelenggaraan Training</label>
                        <textarea class="form-control form-control-smooth" id="tempat_tanggal_training" name="tempat_tanggal_training" rows="2" placeholder="Contoh: 17 - 19 Februari 2025, PAKO Karawang Plant">{{ old('tempat_tanggal_training', $defaultData['tempat_tanggal_training']) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label for="tempat_tanggal_persetujuan" class="form-label-smooth">Tempat &amp; Tanggal Dokumen</label>
                        <input type="text" class="form-control form-control-smooth" id="tempat_tanggal_persetujuan" name="tempat_tanggal_persetujuan" value="{{ old('tempat_tanggal_persetujuan', $defaultData['tempat_tanggal_persetujuan']) }}" placeholder="Contoh: Cikarang, 6 Februari 2025">
                    </div>

                    {{-- Kolom Penyetuju: 2 Penyetuju (Immediate Manager & Direktur) --}}
                    <div class="col-12 mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-primary"></i> Penyetuju Dokumen (Disetujui,)
                        </h6>
                        <small class="text-muted d-block mb-3">Dua pihak penyetuju yang tertera berdampingan pada dokumen cetak PDF.</small>
                    </div>

                    <div class="col-md-6">
                        <label for="nama_im" class="form-label-smooth">Nama Immediate Manager</label>
                        <input type="text" class="form-control form-control-smooth" id="nama_im" name="nama_im" value="{{ old('nama_im', $defaultData['nama_im']) }}" placeholder="Contoh: Tony Herdian">
                    </div>

                    <div class="col-md-6">
                        <label for="bagian_im" class="form-label-smooth">Bagian / Jabatan Immediate Manager</label>
                        <input type="text" class="form-control form-control-smooth" id="bagian_im" name="bagian_im" value="{{ old('bagian_im', $defaultData['bagian_im']) }}" placeholder="Contoh: Business Unit Head Fastener">
                    </div>

                    <div class="col-md-6">
                        <label for="nama_direktur" class="form-label-smooth">Nama Direktur</label>
                        <input type="text" class="form-control form-control-smooth" id="nama_direktur" name="nama_direktur" value="{{ old('nama_direktur', $defaultData['nama_direktur']) }}" placeholder="Contoh: Dian Eka Hartaningsih">
                    </div>

                    <div class="col-md-6">
                        <label for="jabatan_direktur" class="form-label-smooth">Jabatan Direktur</label>
                        <input type="text" class="form-control form-control-smooth" id="jabatan_direktur" name="jabatan_direktur" value="{{ old('jabatan_direktur', $defaultData['jabatan_direktur']) }}" placeholder="Director">
                    </div>

                    {{-- Kolom Konfirmasi DLC --}}
                    <div class="col-12 mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check text-info"></i> Konfirmasi DLC
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label for="konfirmasi_nama" class="form-label-smooth">Nama Konfirmasi DLC</label>
                        <input type="text" class="form-control form-control-smooth" id="konfirmasi_nama" name="konfirmasi_nama" value="{{ old('konfirmasi_nama', $defaultData['konfirmasi_nama']) }}" placeholder="Herwin Gultom">
                    </div>

                    <div class="col-md-6">
                        <label for="konfirmasi_jabatan" class="form-label-smooth">Jabatan Konfirmasi DLC</label>
                        <input type="text" class="form-control form-control-smooth" id="konfirmasi_jabatan" name="konfirmasi_jabatan" value="{{ old('konfirmasi_jabatan', $defaultData['konfirmasi_jabatan']) }}" placeholder="HRD Deputy Div. Head">
                    </div>
                </div>
            </div>

            {{-- FORM ACTIONS BAR --}}
            <div class="bottom-action-panel d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_sent" value="1" id="checkIsSent" {{ old('is_sent', false) ? 'checked' : '' }} style="cursor: pointer;">
                    <label class="form-check-label fw-semibold text-dark small" for="checkIsSent" style="cursor: pointer;">
                        <i class="bi bi-send me-1 text-primary"></i> Buka akses unduh langsung ke akun Immediate Manager (Kirim ke IM)
                    </label>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('penugasan.index') }}" class="btn btn-outline-secondary px-3 py-2 rounded-3">Batal</a>
                    <button type="submit" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="submit" name="action_save_send" value="1" class="btn btn-gradient-blue px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-send-fill"></i> Simpan &amp; Kirim ke IM
                    </button>
                    <button type="submit" name="action_save_download" value="1" class="btn btn-gradient-green px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Simpan &amp; Unduh PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

{{-- JAVASCRIPT FOR DYNAMIC PARTICIPANTS & CALCULATION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('pesertaTableBody');
    const btnAdd = document.getElementById('btnAddParticipant');
    const staffPicker = document.getElementById('staffQuickPicker');
    const inputJumlahPeserta = document.getElementById('jumlah_peserta');
    const inputBiaya = document.getElementById('biaya_per_peserta');
    const totalDisplay = document.getElementById('total_biaya_display');
    const inputTerbilang = document.getElementById('terbilang');

    let rowIndex = document.querySelectorAll('.peserta-row').length;

    function recalculate() {
        const rows = document.querySelectorAll('.peserta-row');
        const count = rows.length;
        inputJumlahPeserta.value = count;

        const biaya = parseFloat(inputBiaya.value) || 0;
        const total = count * biaya;
        totalDisplay.value = new Intl.NumberFormat('id-ID').format(total);

        // Fetch terbilang from server if total > 0
        if (total > 0) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch("{{ route('penugasan.store') }}?action=terbilang", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ amount: total })
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.terbilang) {
                    inputTerbilang.value = data.terbilang;
                }
            })
            .catch(() => {});
        } else {
            inputTerbilang.value = '';
        }
    }

    // Input biaya event
    inputBiaya?.addEventListener('input', recalculate);

    // Renumber rows
    function renumber() {
        const rows = document.querySelectorAll('.peserta-row');
        rows.forEach((row, i) => {
            row.querySelector('.row-number').textContent = i + 1;
        });
        recalculate();
    }

    // Add empty row
    function addRow(data = {}) {
        const tr = document.createElement('tr');
        tr.className = 'peserta-row';
        tr.dataset.index = rowIndex;

        tr.innerHTML = `
            <td class="text-center row-number fw-bold text-muted">${rowIndex + 1}</td>
            <td><input type="text" class="form-control form-control-sm form-control-smooth input-npk" name="peserta[${rowIndex}][npk]" value="${data.npk || ''}" placeholder="NPK"></td>
            <td><input type="text" class="form-control form-control-sm form-control-smooth input-nama" name="peserta[${rowIndex}][nama]" value="${data.nama || ''}" placeholder="Nama Lengkap" required></td>
            <td><input type="text" class="form-control form-control-sm form-control-smooth input-bagian" name="peserta[${rowIndex}][bagian]" value="${data.bagian || ''}" placeholder="Bagian / Dept"></td>
            <td><input type="text" class="form-control form-control-sm form-control-smooth input-jabatan" name="peserta[${rowIndex}][jabatan]" value="${data.jabatan || ''}" placeholder="Jabatan"></td>
            <td><input type="text" class="form-control form-control-sm form-control-smooth input-atasan" name="peserta[${rowIndex}][atasan]" value="${data.atasan || ''}" placeholder="Atasan Langsung"></td>
            <td><input type="text" class="form-control form-control-sm form-control-smooth text-center" name="peserta[${rowIndex}][paraf]" value="" placeholder="-"></td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center btn-delete-row" style="width: 30px; height: 30px;" title="Hapus Baris">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(tr);
        rowIndex++;
        renumber();
    }

    btnAdd?.addEventListener('click', () => addRow());

    // Quick Picker
    staffPicker?.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt.value) return;

        addRow({
            npk: opt.dataset.npk,
            nama: opt.dataset.nama,
            bagian: opt.dataset.bagian,
            jabatan: opt.dataset.jabatan,
            atasan: opt.dataset.atasan,
        });

        this.value = '';
    });

    // Delete row event
    tableBody?.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-row');
        if (btn) {
            const rows = document.querySelectorAll('.peserta-row');
            if (rows.length <= 1) {
                alert('Minimal harus ada 1 peserta training.');
                return;
            }
            btn.closest('tr').remove();
            renumber();
        }
    });

    // Initial calculation
    recalculate();
});
</script>
@endsection
