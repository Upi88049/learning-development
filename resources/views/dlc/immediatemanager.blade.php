@extends('layouts.admindlc')

@section('title', 'Member List | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Management</p>
                    <h1 class="h3 mb-1">Member List</h1>
                </div>
            </div>
            <div class="heading-actions d-flex flex-wrap gap-2">
                <a class="btn btn-outline-success btn-sm" href="{{ route('staff.export', ['department' => $selectedDepartment]) }}">
                    <i class="bi bi-download me-1" aria-hidden="true"></i> Export Excel {{ $selectedDepartment ? '('.$selectedDepartment.')' : '' }}
                </a>
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#modalImportStaff">
                    <i class="bi bi-upload me-1" aria-hidden="true"></i> Import Excel
                </button>
                <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}">
                    <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Tambah Staff
                </a>
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

        <form id="bulkDeleteForm" action="{{ route('staff.bulkDestroy') }}" method="POST" onsubmit="return confirmBulkDelete()">
            @csrf
            @method('DELETE')

            <section class="panel mt-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Member List</span></h2>
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 ms-auto">
                        <button type="submit" id="btnBulkDelete" class="btn btn-danger btn-sm d-none">
                            <i class="bi bi-trash me-1"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                        </button>
                        
                        {{-- Dropdown Filter Department --}}
                        <div class="d-flex align-items-center gap-1">
                            <label for="filterDepartment" class="small text-muted mb-0 d-none d-sm-inline"><i class="bi bi-funnel"></i></label>
                            <select id="filterDepartment" class="form-select form-select-sm" style="min-width: 170px;" onchange="window.location.href='{{ route('member-list') }}' + (this.value ? '?department=' + encodeURIComponent(this.value) : '')">
                                <option value="">-- Semua Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->nama_department }}" {{ $selectedDepartment == $dept->nama_department ? 'selected' : '' }}>
                                        {{ $dept->nama_department }}
                                    </option>
                                @endforeach
                            </select>
                            @if($selectedDepartment)
                                <a href="{{ route('member-list') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>

                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search staff" data-table-search="usersTable" aria-label="Search staff">
                    </div>
                </div>
                <div>
                    @if($selectedDepartment)
                            <small class="text-muted">Menampilkan data filter department: <strong class="text-primary">{{ $selectedDepartment }}</strong></small>
                        @endif
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40px;" class="text-center">
                                    <input type="checkbox" class="form-check-input" id="selectAll" title="Pilih Semua">
                                </th>
                                <th scope="col">NPK</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Tanggal Lahir</th>
                                <th scope="col">Umur</th>
                                <th scope="col">Department</th>
                                <th scope="col">Nama Immediate Manager</th>
                                <th scope="col">Level Jabatan</th>
                                <th scope="col" class="text-end" style="width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($staff as $s)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="staff_ids[]" value="{{ $s->id_staff }}" class="form-check-input staff-checkbox">
                                </td>
                                <td>{{ $s->npk_staff }}</td>
                                <td>
                                    <p class="mb-0">
                                        <a href="{{ route('staff.detail', $s->id_staff) }}" class="fw-semibold text-primary text-decoration-none" title="Lihat Detail Training">
                                            {{ $s->nama_staff }}
                                        </a>
                                    </p>
                                </td>
                                <td>{{ $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $s->umur }}</span></td>
                                <td>{{ $s->department ? $s->department->nama_department : '-' }}</td>
                                <td>{{ $s->immediateManager ? $s->immediateManager->nama_staff : '-' }}</td>
                                <td>{{ $s->levelJabatan ? $s->levelJabatan->kode_level_jabatan : '-' }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group" aria-label="Aksi Staff">
                                        <a class="btn btn-outline-info btn-sm" href="{{ route('staff.detail', $s->id_staff) }}" title="Lihat Detail Training">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('staff.edit', $s->id_staff) }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmSingleDelete('{{ $s->id_staff }}', '{{ addslashes($s->nama_staff) }}')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data staff{{ $selectedDepartment ? ' untuk department '.$selectedDepartment : '' }}.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
                    <p class="text-muted small mb-0">Total {{ count($staff) }} staff terdaftar {{ $selectedDepartment ? 'pada department '.$selectedDepartment : '' }}</p>
                </div>
            </section>
        </form>
    </div>
</main>

{{-- Modal Import Staff --}}
<div class="modal fade" id="modalImportStaff" tabindex="-1" aria-labelledby="modalImportStaffLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('staff.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportStaffLabel"><i class="bi bi-file-earmark-excel me-2 text-success"></i>Import Data Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 bg-light border rounded mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold small"><i class="bi bi-info-circle me-1 text-primary"></i>Format Template:</span>
                            <a href="{{ route('staff.template') }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i> Download Format Excel
                            </a>
                        </div>
                        <small class="text-muted d-block">
                            Pastikan format kolom sesuai: <strong>NPK, Nama Staff, Tanggal Lahir (YYYY-MM-DD), Department, Level Jabatan, NPK Immediate Manager</strong>.
                        </small>
                        <small class="text-primary d-block mt-1">
                            <i class="bi bi-check2-circle me-1"></i> Jika NPK sudah ada di database, data kolom yang berubah akan otomatis <strong>diperbarui (Update)</strong>.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="file_import" class="form-label fw-semibold">Pilih Berkas Excel / CSV <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="file_import" name="file_import" accept=".csv, .txt, .xlsx, .xls" required>
                        <div class="form-text">Mendukung format <code>.xls</code>, <code>.csv</code>, <code>.xlsx</code>. Maksimal 5 MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Upload &amp; Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Form Terpisah untuk Hapus Single Staff --}}
<form id="singleDeleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkDeleteState() {
        const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
        const count = checkedBoxes.length;
        if (selectedCountSpan) selectedCountSpan.textContent = count;

        if (btnBulkDelete) {
            if (count > 0) {
                btnBulkDelete.classList.remove('d-none');
            } else {
                btnBulkDelete.classList.add('d-none');
            }
        }

        if (selectAll && staffCheckboxes.length > 0) {
            selectAll.checked = (count === staffCheckboxes.length);
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            staffCheckboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row && row.style.display !== 'none') {
                    cb.checked = selectAll.checked;
                }
            });
            updateBulkDeleteState();
        });
    }

    staffCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteState);
    });
});

function confirmBulkDelete() {
    const count = document.querySelectorAll('.staff-checkbox:checked').length;
    if (count === 0) {
        alert('Silakan pilih minimal satu staff untuk dihapus.');
        return false;
    }
    return confirm('Apakah Anda yakin ingin menghapus ' + count + ' data staff yang dipilih?');
}

function confirmSingleDelete(id, name) {
    if (confirm('Apakah Anda yakin ingin menghapus staff "' + name + '"?')) {
        const form = document.getElementById('singleDeleteForm');
        form.action = '{{ url("/staff/destroy") }}/' + id;
        form.submit();
    }
}
</script>

@endsection