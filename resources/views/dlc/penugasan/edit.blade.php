@extends('layouts.admindlc')

@section('content')
<main class="admin-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Heading --}}
        <div class="page-heading mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Edit Formulir Pendaftaran &amp; Penugasan Training</h1>
                    <p class="text-muted mb-0">Perbarui data formulir penugasan training ({{ $penugasan->nama_training }}).</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('penugasan.previewPdf', $penugasan->id_penugasan) }}" target="_blank" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i> Preview PDF
                </a>
                <a href="{{ route('penugasan.downloadPdf', $penugasan->id_penugasan) }}" class="btn btn-outline-success">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
                <a href="{{ route('penugasan.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Errors / Alerts --}}
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Main Form --}}
        <form action="{{ route('penugasan.update', $penugasan->id_penugasan) }}" method="POST" id="formPenugasan">
            @csrf
            @method('PUT')

            {{-- 1. INFORMASI UMUM TRAINING --}}
            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3 section-title border-bottom pb-2">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    <span>1. Informasi Training &amp; Perusahaan</span>
                </h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_training" class="form-label fw-semibold">Nama Training <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_training" name="nama_training" value="{{ old('nama_training', $penugasan->nama_training) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_training" class="form-label fw-semibold">Jenis Training <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_training" name="jenis_training" value="{{ old('jenis_training', $penugasan->jenis_training) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="sub_co" class="form-label fw-semibold">SubCo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sub_co" name="sub_co" value="{{ old('sub_co', $penugasan->sub_co) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="divisi" class="form-label fw-semibold">Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="divisi" name="divisi" value="{{ old('divisi', $penugasan->divisi) }}" required>
                    </div>

                    <div class="col-md-2">
                        <label for="no_form" class="form-label fw-semibold">No. Dokumen</label>
                        <input type="text" class="form-control" id="no_form" name="no_form" value="{{ old('no_form', $penugasan->no_form) }}">
                    </div>
                </div>
            </div>

            {{-- 2. TABEL PESERTA TRAINING --}}
            <div class="panel p-4 mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-2 mb-3">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-people me-2 text-primary"></i>
                        <span>2. Data Peserta Training</span>
                    </h2>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="staffQuickPicker" style="max-width: 250px;">
                            <option value="">-- Tambah Cepat dari Staff --</option>
                            @foreach($allStaff as $st)
                            <option value="{{ $st->id_staff }}" 
                                data-npk="{{ $st->npk_staff }}" 
                                data-nama="{{ $st->nama_staff }}"
                                data-bagian="{{ $st->department ? $st->department->nama_department : '-' }}"
                                data-jabatan="{{ $st->levelJabatan ? $st->levelJabatan->kode_level_jabatan : 'SF' }}"
                                data-atasan="{{ $st->immediateManager ? $st->immediateManager->nama_staff : '-' }}">
                                {{ $st->nama_staff }} ({{ $st->npk_staff }})
                            </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnAddParticipant">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Baris Peserta
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle small" id="tablePeserta">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 50px;">No.</th>
                                <th style="width: 140px;">NPK</th>
                                <th>Nama Lengkap Peserta <span class="text-danger">*</span></th>
                                <th style="width: 180px;">Bagian</th>
                                <th style="width: 120px;">Jabatan</th>
                                <th>Atasan Langsung</th>
                                <th style="width: 100px;">Paraf Atasan</th>
                                <th style="width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pesertaTableBody">
                            @php
                                $pesertaRows = old('peserta', $penugasan->peserta);
                                if (empty($pesertaRows)) {
                                    $pesertaRows = [['npk' => '', 'nama' => '', 'bagian' => '', 'jabatan' => '', 'atasan' => '', 'paraf' => '']];
                                }
                            @endphp
                            @foreach($pesertaRows as $idx => $p)
                            <tr class="peserta-row" data-index="{{ $idx }}">
                                <td class="text-center row-number fw-semibold">{{ $idx + 1 }}</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-npk" name="peserta[{{ $idx }}][npk]" value="{{ $p['npk'] ?? '' }}" placeholder="NPK">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-nama" name="peserta[{{ $idx }}][nama]" value="{{ $p['nama'] ?? '' }}" placeholder="Nama Lengkap" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-bagian" name="peserta[{{ $idx }}][bagian]" value="{{ $p['bagian'] ?? '' }}" placeholder="Bagian / Dept">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-jabatan" name="peserta[{{ $idx }}][jabatan]" value="{{ $p['jabatan'] ?? '' }}" placeholder="Jabatan">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-atasan" name="peserta[{{ $idx }}][atasan]" value="{{ $p['atasan'] ?? '' }}" placeholder="Atasan Langsung">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center" name="peserta[{{ $idx }}][paraf]" value="{{ $p['paraf'] ?? '' }}" placeholder="-">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-delete-row" title="Hapus Baris">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted d-block mt-1">* Mohon data peserta diisi dengan lengkap.</small>
            </div>

            {{-- 3. BIAYA INVESTASI & KALKULASI --}}
            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3 section-title border-bottom pb-2">
                    <i class="bi bi-cash-stack me-2 text-primary"></i>
                    <span>3. Biaya Investasi Pelatihan</span>
                </h2>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="jumlah_peserta" class="form-label fw-semibold">Jumlah Peserta</label>
                        <input type="number" class="form-control bg-light" id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', $penugasan->jumlah_peserta) }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="biaya_per_peserta" class="form-label fw-semibold">Biaya Investasi per Peserta (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="any" class="form-control" id="biaya_per_peserta" name="biaya_per_peserta" value="{{ old('biaya_per_peserta', (float)$penugasan->biaya_per_peserta) }}" placeholder="0" required>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="total_biaya" class="form-label fw-semibold">Total Biaya Investasi (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control bg-light fw-bold text-success" id="total_biaya_display" value="{{ number_format($penugasan->total_biaya, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="terbilang" class="form-label fw-semibold">Terbilang</label>
                        <input type="text" class="form-control bg-light" id="terbilang" name="terbilang" value="{{ old('terbilang', $penugasan->terbilang) }}">
                        <small class="text-muted d-block mt-1"><em>*) Note : Biaya Ditanggung Perusahaan</em></small>
                    </div>
                </div>
            </div>

            {{-- 4. ALASAN MENGIKUTI PELATIHAN --}}
            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3 section-title border-bottom pb-2">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i>
                    <span>4. Alasan Mengikuti Pelatihan</span>
                </h2>

                <div class="mb-3">
                    <label for="alasan_pelatihan" class="form-label fw-semibold">Uraian Alasan Pelatihan</label>
                    <textarea class="form-control" id="alasan_pelatihan" name="alasan_pelatihan" rows="3">{{ old('alasan_pelatihan', $penugasan->alasan_pelatihan) }}</textarea>
                </div>
            </div>

            {{-- 5. DATA PERSETUJUAN DIVISI & PENYELENGGARAAN --}}
            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3 section-title border-bottom pb-2">
                    <i class="bi bi-pen me-2 text-primary"></i>
                    <span>5. Data Persetujuan Divisi &amp; Pelaksanaan</span>
                </h2>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="nama_atasan" class="form-label fw-semibold">Nama Atasan (Div/Dept)</label>
                        <input type="text" class="form-control" id="nama_atasan" name="nama_atasan" value="{{ old('nama_atasan', $penugasan->nama_atasan) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="divisi_atasan" class="form-label fw-semibold">Divisi Atasan</label>
                        <input type="text" class="form-control" id="divisi_atasan" name="divisi_atasan" value="{{ old('divisi_atasan', $penugasan->divisi_atasan) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="jabatan_atasan" class="form-label fw-semibold">Jabatan Atasan</label>
                        <input type="text" class="form-control" id="jabatan_atasan" name="jabatan_atasan" value="{{ old('jabatan_atasan', $penugasan->jabatan_atasan) }}">
                    </div>

                    <div class="col-12">
                        <label for="tempat_tanggal_training" class="form-label fw-semibold">Tempat &amp; Tanggal Penyelenggaraan Training</label>
                        <textarea class="form-control" id="tempat_tanggal_training" name="tempat_tanggal_training" rows="2">{{ old('tempat_tanggal_training', $penugasan->tempat_tanggal_training) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="tempat_tanggal_persetujuan" class="form-label fw-semibold">Tempat &amp; Tanggal Dokumen</label>
                        <input type="text" class="form-control" id="tempat_tanggal_persetujuan" name="tempat_tanggal_persetujuan" value="{{ old('tempat_tanggal_persetujuan', $penugasan->tempat_tanggal_persetujuan) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="penyetujui_jabatan" class="form-label fw-semibold">Jabatan Penyetujui (Disetujui,)</label>
                        <input type="text" class="form-control" id="penyetujui_jabatan" name="penyetujui_jabatan" value="{{ old('penyetujui_jabatan', $penugasan->penyetujui_jabatan) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="konfirmasi_nama" class="form-label fw-semibold">Nama Konfirmasi DLC</label>
                        <input type="text" class="form-control" id="konfirmasi_nama" name="konfirmasi_nama" value="{{ old('konfirmasi_nama', $penugasan->konfirmasi_nama) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="konfirmasi_jabatan" class="form-label fw-semibold">Jabatan Konfirmasi DLC</label>
                        <input type="text" class="form-control" id="konfirmasi_jabatan" name="konfirmasi_jabatan" value="{{ old('konfirmasi_jabatan', $penugasan->konfirmasi_jabatan) }}">
                    </div>
                </div>
            </div>

            {{-- FORM ACTIONS --}}
            <div class="d-flex flex-wrap justify-content-end gap-3 my-4">
                <a href="{{ route('penugasan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <button type="submit" name="action_save_download" value="1" class="btn btn-success px-4">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Simpan &amp; Download PDF
                </button>
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

    function updateRowNumbersAndCount() {
        const rows = document.querySelectorAll('.peserta-row');
        rows.forEach((row, i) => {
            row.querySelector('.row-number').textContent = i + 1;
        });
        inputJumlahPeserta.value = rows.length;
        calculateTotal();
    }

    function calculateTotal() {
        const count = parseInt(inputJumlahPeserta.value) || 0;
        const biaya = parseFloat(inputBiaya.value) || 0;
        const total = count * biaya;

        totalDisplay.value = new Intl.NumberFormat('id-ID').format(total);
        if (total > 0 && (!inputTerbilang.dataset.manuallyEdited || inputTerbilang.value === '')) {
            inputTerbilang.value = terbilangRupiah(total);
        }
    }

    function addRow(npk = '', nama = '', bagian = '', jabatan = '', atasan = '', paraf = '') {
        const tr = document.createElement('tr');
        tr.className = 'peserta-row';
        tr.dataset.index = rowIndex;

        tr.innerHTML = `
            <td class="text-center row-number fw-semibold"></td>
            <td>
                <input type="text" class="form-control form-control-sm input-npk" name="peserta[${rowIndex}][npk]" value="${npk}" placeholder="NPK">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm input-nama" name="peserta[${rowIndex}][nama]" value="${nama}" placeholder="Nama Lengkap" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm input-bagian" name="peserta[${rowIndex}][bagian]" value="${bagian}" placeholder="Bagian / Dept">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm input-jabatan" name="peserta[${rowIndex}][jabatan]" value="${jabatan}" placeholder="Jabatan">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm input-atasan" name="peserta[${rowIndex}][atasan]" value="${atasan}" placeholder="Atasan Langsung">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm text-center" name="peserta[${rowIndex}][paraf]" value="${paraf}" placeholder="-">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-delete-row" title="Hapus Baris">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(tr);
        rowIndex++;
        updateRowNumbersAndCount();
    }

    btnAdd.addEventListener('click', function () {
        addRow();
    });

    staffPicker.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            addRow(
                opt.dataset.npk || '',
                opt.dataset.nama || '',
                opt.dataset.bagian || '',
                opt.dataset.jabatan || '',
                opt.dataset.atasan || '',
                ''
            );
            this.value = '';
        }
    });

    tableBody.addEventListener('click', function (e) {
        if (e.target.closest('.btn-delete-row')) {
            const rows = document.querySelectorAll('.peserta-row');
            if (rows.length > 1) {
                e.target.closest('.peserta-row').remove();
                updateRowNumbersAndCount();
            } else {
                alert('Minimal 1 baris peserta harus diisi.');
            }
        }
    });

    inputBiaya.addEventListener('input', calculateTotal);
    inputTerbilang.addEventListener('input', function () {
        this.dataset.manuallyEdited = '1';
    });

    function terbilangRupiah(number) {
        const words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        function toWords(n) {
            n = Math.floor(n);
            if (n < 12) return words[n];
            if (n < 20) return words[n - 10] + ' Belas';
            if (n < 100) return words[Math.floor(n / 10)] + ' Puluh ' + toWords(n % 10);
            if (n < 200) return 'Seratus ' + toWords(n - 100);
            if (n < 1000) return words[Math.floor(n / 100)] + ' Ratus ' + toWords(n % 100);
            if (n < 2000) return 'Seribu ' + toWords(n - 1000);
            if (n < 1000000) return toWords(Math.floor(n / 1000)) + ' Ribu ' + toWords(n % 1000);
            if (n < 1000000000) return toWords(Math.floor(n / 1000000)) + ' Juta ' + toWords(n % 1000000);
            if (n < 1000000000000) return toWords(Math.floor(n / 1000000000)) + ' Miliar ' + toWords(n % 1000000000);
            return '';
        }
        if (number <= 0) return 'Nol Rupiah';
        return (toWords(number) + ' Rupiah').replace(/\s+/g, ' ').trim();
    }

    calculateTotal();
});
</script>
@endsection
