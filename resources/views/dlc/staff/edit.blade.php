@extends('layouts.admindlc')

@section('title', 'Edit Staff | Learning & Development')

@section('content')

<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-gear" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">Management</p>
          <h1 class="h3 mb-1">Edit Data Staff</h1>
          <p class="text-muted mb-0">Perbarui data profil, jabatan, dan penugasan staff.</p>
        </div>
      </div>
      <div class="heading-actions">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('member-list') }}">
          <i class="bi bi-arrow-left me-1"></i> Kembali ke Staff List
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
      <div class="col-12 col-xl-8">
        <form action="{{ route('staff.update', $staff->id_staff) }}" method="POST" class="panel p-4">
          @csrf
          @method('PUT')
          <div class="panel-header border-bottom pb-3 mb-3">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-card-heading me-2" aria-hidden="true"></i><span>Informasi Staff</span></h2>
              <p class="text-muted mb-0">Ubah informasi NPK, nama, tanggal lahir, dan penugasan departemen.</p>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="npk_staff">NPK Staff <span class="text-danger">*</span></label>
              <input class="form-control" id="npk_staff" name="npk_staff" type="number" value="{{ old('npk_staff', $staff->npk_staff) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold" for="nama_staff">Nama Lengkap <span class="text-danger">*</span></label>
              <input class="form-control" id="nama_staff" name="nama_staff" type="text" value="{{ old('nama_staff', $staff->nama_staff) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold" for="tanggal_lahir">Tanggal Lahir</label>
              <input class="form-control" id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $staff->tanggal_lahir ? $staff->tanggal_lahir->format('Y-m-d') : '') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold" for="id_department">Department <span class="text-danger">*</span></label>
              <select class="form-select" id="id_department" name="id_department" required>
                <option value="">-- Pilih Department --</option>
                @foreach($departments as $dept)
                  <option value="{{ $dept->id_department }}" {{ old('id_department', $staff->id_department) == $dept->id_department ? 'selected' : '' }}>
                    {{ $dept->nama_department }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold" for="id_jabatan_staff">Level Jabatan <span class="text-danger">*</span></label>
              <select class="form-select" id="id_jabatan_staff" name="id_jabatan_staff" required>
                <option value="">-- Pilih Level Jabatan --</option>
                @foreach($levels as $lvl)
                  <option value="{{ $lvl->id_level_jabatan }}" {{ old('id_jabatan_staff', $staff->id_jabatan_staff) == $lvl->id_level_jabatan ? 'selected' : '' }}>
                    {{ $lvl->kode_level_jabatan }} {{ $lvl->keterangan ? '('.$lvl->keterangan.')' : '' }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold" for="id_immediate_manager">Immediate Manager</label>
              <select class="form-select" id="id_immediate_manager" name="id_immediate_manager">
                <option value="">-- Tanpa / Tidak Ada Manager --</option>
                @foreach($managers as $mgr)
                  <option value="{{ $mgr->id_staff }}" {{ old('id_immediate_manager', $staff->id_immediate_manager) == $mgr->id_staff ? 'selected' : '' }}>
                    {{ $mgr->nama_staff }} (NPK: {{ $mgr->npk_staff }})
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a class="btn btn-outline-secondary" href="{{ route('immediate-manager') }}">Batal</a>
            <button class="btn btn-primary" type="submit">
              <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </section>
  </div>
</main>

@endsection
