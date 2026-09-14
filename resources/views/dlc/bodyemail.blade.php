@extends('layouts.admindlc')

@section('title', 'Body Email Notifikasi | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-card-text"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Setting Notifikasi</span>
                            <span class="text-muted small">Template Pesan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Pengaturan Body Email</h1>
                        <p class="text-muted mb-0 small">Atur daftar penerima, subjek, dan template konten pesan email notifikasi TNA bagi Immediate Manager.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('periode-tna') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Periode TNA
                    </a>
                    <a href="{{ route('penerima-email') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-people me-1"></i> Kelola Penerima
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 small">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-3">
            <!-- ========== KOLOM DAFTAR PENERIMA (KONFIGURASI PREVIOUS) ========== -->
            <div class="col-12 col-xl-4">
                <div class="panel h-100 p-4">
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-people me-2" aria-hidden="true"></i>
                            <span>Daftar Penerima (Immediate Manager)</span>
                        </h2>
                        <p class="text-muted small mb-0">Alamat email yang disimpan dari halaman Konfigurasi Penerima Email.</p>
                    </div>

                    <div class="penerima-list mt-3">
                        @forelse($recipientsList as $email)
                        <div class="d-flex align-items-center p-2 mb-2 bg-light border rounded">
                            <i class="bi bi-person-check text-primary me-2 fs-5"></i>
                            <div>
                                <strong class="d-block text-dark small">{{ $email }}</strong>
                                <small class="text-muted">Target Notifikasi</small>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-warning text-center small mb-0">
                            Belum ada konfigurasi email penerima. 
                            <a href="{{ route('penerima-email') }}" class="alert-link">Atur di sini</a>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('penerima-email') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit Penerima
                        </a>
                    </div>
                </div>
            </div>

            <!-- ========== FORM SUBJEK & ISI BODY EMAIL ========== -->
            <div class="col-12 col-xl-8">
                <form action="{{ route('body-email.store') }}" method="POST" enctype="multipart/form-data" class="panel p-4">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-envelope-paper me-2" aria-hidden="true"></i>
                            <span>Form Subjek &amp; Isi Email</span>
                        </h2>
                        <p class="text-muted mb-0">Isi email ditujukan kepada Immediate Manager berisi link ke dashboard untuk melihat staff dan training staff-nya.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="subject">Subjek Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $subject) }}" placeholder="Subjek email..." required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="body">Isi Body Email <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="body" name="body" rows="10" required>{{ old('body', $body) }}</textarea>
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i> Pastikan tautan ke dashboard Immediate Manager disertakan: 
                                <code>http://localhost/learningDevelopment/public/users</code>
                            </div>
                        </div>

                        <!-- ========== UPLOAD ATTACHMENT (GUIDANCE) ========== -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                    <label class="form-label fw-semibold mb-0" for="attachment">
                                        <i class="bi bi-paperclip text-primary me-1"></i> File Attachment Panduan (Guidance)
                                    </label>
                                    @if($hasAttachment)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">
                                            <i class="bi bi-check-circle me-1"></i> Lampiran Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1">
                                            Belum Ada Lampiran
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-3">
                                    Unggah file pedoman / panduan pengisian TNA untuk Immediate Manager. File ini akan dilampirkan secara otomatis pada email notifikasi yang dikirimkan ke setiap Immediate Manager.
                                </p>

                                @if($hasAttachment)
                                <div class="card border border-primary border-opacity-25 bg-white mb-3 shadow-sm">
                                    <div class="card-body p-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                                                <i class="bi bi-file-earmark-arrow-down"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark text-break">{{ $attachmentName }}</div>
                                                <div class="text-muted small">
                                                    Ukuran: {{ $formattedSize }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('body-email.download-attachment') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-download"></i> Unduh File
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" onclick="if(confirm('Apakah Anda yakin ingin menghapus file lampiran panduan ini?')) document.getElementById('form-delete-attachment').submit();">
                                                <i class="bi bi-trash"></i> Hapus Lampiran
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text mb-2 text-primary small">
                                    <i class="bi bi-info-circle me-1"></i> Pilih file baru di bawah ini jika ingin mengganti file panduan di atas:
                                </div>
                                @endif

                                <div class="input-group">
                                    <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip">
                                </div>
                                @error('attachment')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted mt-2 small">
                                    <i class="bi bi-info-circle me-1"></i> Format yang didukung: <strong>PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP</strong> (Maksimal 20 MB).
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#sendEmailModal">
                                <i class="bi bi-send me-1"></i> Kirim Notifikasi Email ke IM
                            </button>
                            <span class="text-muted small d-none d-sm-inline">({{ count($recipientsList) }} penerima)</span>
                        </div>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Konfigurasi Body Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Form Hidden untuk Hapus Attachment -->
    <form id="form-delete-attachment" action="{{ route('body-email.delete-attachment') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Konfirmasi Kirim Email Langsung -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('periode-tna.sendEmail') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="sendEmailModalLabel">
                            <i class="bi bi-send text-success me-2"></i> Konfirmasi Kirim Notifikasi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-secondary mb-3">Apakah Anda yakin ingin mengirimkan email notifikasi TNA ini kepada seluruh Immediate Manager terdaftar?</p>
                        <div class="p-3 bg-light rounded border small">
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted">Total Penerima:</span>
                                <span class="fw-semibold text-dark">{{ count($recipientsList) }} alamat email</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block mb-1">Subjek Email:</span>
                                <span class="fw-semibold text-dark">{{ $subject }}</span>
                            </div>
                            <div>
                                <span class="text-muted d-block mb-1">File Lampiran (Guidance):</span>
                                @if($hasAttachment)
                                    <span class="text-success fw-semibold"><i class="bi bi-paperclip me-1"></i> {{ $attachmentName }} ({{ $formattedSize }})</span>
                                @else
                                    <span class="text-muted fst-italic">Tidak ada file lampiran</span>
                                @endif
                            </div>
                        </div>
                        <div class="alert alert-warning py-2 px-3 mt-3 mb-0 small">
                            <i class="bi bi-exclamation-circle me-1"></i> Pastikan Anda sudah mengklik <strong>"Simpan Konfigurasi"</strong> bila baru saja mengubah subjek, isi pesan, atau mengunggah lampiran baru sebelum mengirim.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send me-1"></i> Ya, Kirim Email Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection