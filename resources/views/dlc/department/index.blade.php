@extends('layouts.admindlc')

@section('title', 'Master Department | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Master Data</p>
                    <h1 class="h3 mb-1">Daftar Department</h1>
                </div>
            </div>
            <div class="heading-actions">
                <a class="btn btn-primary btn-sm" href="{{ route('department.create') }}">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Department
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

        <section class="panel mt-3">
            <div class="panel-header">
                <div>
                    <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Department List</span></h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <input class="form-control form-control-sm table-search" type="search" placeholder="Search department" data-table-search="deptTable" aria-label="Search department">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="deptTable" data-searchable-table>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 60px;">No</th>
                            <th scope="col">Nama Department</th>
                            <th scope="col">Divisi</th>
                            <th scope="col" class="text-center" style="width: 150px;">Jumlah Staff</th>
                            <th scope="col" class="text-end" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $d->nama_department }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $d->divisi ? $d->divisi->nama_divisi : '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $d->staff_count }} Staff</span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-outline-primary btn-sm me-1" href="{{ route('department.edit', $d->id_department) }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('department.destroy', $d->id_department) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus department ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data department.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
                <p class="text-muted small mb-0">Total {{ count($departments) }} department terdaftar</p>
            </div>
        </section>
    </div>
</main>

@endsection
