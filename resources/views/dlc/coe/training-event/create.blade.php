@extends('layouts.admindlc')

@section('title', 'Buat Training Event (COE) | Dharma Learning Center')

@section('content')
<style>
.form-section-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    margin-bottom: 1.5rem;
    overflow: hidden;
}
.form-section-header {
    background: #f8fafc;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.form-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.form-section-body {
    padding: 1.5rem;
}

/* Radio button pills for Tipe Penyelenggara */
.radio-tile-group {
    display: flex;
    gap: 1rem;
}
.radio-tile-label {
    flex: 1;
    cursor: pointer;
    margin-bottom: 0;
}
.radio-tile-input {
    position: absolute;
    opacity: 0;
}
.radio-tile-box {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s ease;
    background: #ffffff;
}
.radio-tile-input:checked + .radio-tile-box {
    border-color: #0284c7;
    background: #f0f9ff;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}
.radio-tile-icon {
    font-size: 1.5rem;
    color: #64748b;
}
.radio-tile-input:checked + .radio-tile-box .radio-tile-icon {
    color: #0284c7;
}

/* Dynamic table styling */
.dynamic-table thead th {
    background: #f8fafc;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #475569;
    padding: 10px 12px;
    border-bottom: 1.5px solid #e2e8f0;
}
.dynamic-table tbody td {
    padding: 8px 10px;
    vertical-align: middle;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <a href="{{ route('coe.training-event.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Training Event
                </a>
                <h1 class="h3 mb-0 fw-bold text-dark">Buat Data Training Event</h1>
                <p class="text-muted small mb-0">Lengkapi data penyelenggaraan, trainer, fasilitas ruangan, tipe evaluasi, serta daftar peserta pelatihan.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('coe.kalender.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-calendar-week"></i> Buka Kalender
                </a>
            </div>
        </div>

        {{-- Error Alerts --}}
        @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <strong>Terdapat kesalahan pengisian formulir:</strong>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('coe.training-event.store') }}" method="POST" id="formCreateTrainingEvent">
            @csrf

            {{-- 1. PILIH EVENT KALENDER (RELASI) --}}
            <div class="form-section-card">
                <div class="form-section-header">
                    <h2 class="form-section-title">
                        <i class="bi bi-calendar3 text-primary"></i> 1. Hubungkan dengan Event Kalender (COE)
                    </h2>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Opsional / Terintegrasi</span>
                </div>
                <div class="form-section-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold">Pilih Agenda Event Kalender</label>
                            <select name="id_event" id="selectEventKalender" class="form-select" onchange="handleEventKalenderChange()">
                                <option value="">-- Buat Training Event Mandiri (Tanpa Relasi Kalender) --</option>
                                @foreach($allEvents as $ev)
                                <option value="{{ $ev->id_event }}" 
                                        {{ (old('id_event', $selectedEventId) == $ev->id_event) ? 'selected' : '' }}
                                        data-no="{{ $ev->no_event }}"
                                        data-nama="{{ $ev->nama_training }}"
                                        data-batch="{{ $ev->batch_training }}"
                                        data-tanggal="{{ $ev->tanggal_per_batch->format('Y-m-d') }}"
                                        data-tanggal-fmt="{{ $ev->date_range_formatted }}"
                                        data-tna="{{ $ev->jumlah_peserta_tna }}"
                                        data-nontna="{{ $ev->jumlah_peserta_non_tna }}"
                                        data-biaya="{{ $ev->formatted_biaya }}">
                                    [{{ $ev->no_event }}] {{ $ev->nama_training }} - {{ $ev->batch_training }} ({{ $ev->date_range_formatted }})
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Jika dipilih, informasi jadwal dan kuota peserta akan otomatis diselaraskan dengan event kalender.</div>
                        </div>

                        <div class="col-12 col-md-4" id="boxEventPreview" style="display: none;">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="small text-muted mb-1">Informasi Event Terpilih:</div>
                                <div class="fw-bold text-dark" id="previewEventNama">-</div>
                                <div class="small text-primary font-monospace" id="previewEventNo">-</div>
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-calendar-event me-1"></i><span id="previewEventTanggal">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. PENYELENGGARA & PELAKSANAAN --}}
            <div class="form-section-card">
                <div class="form-section-header">
                    <h2 class="form-section-title">
                        <i class="bi bi-person-video3 text-primary"></i> 2. Penyelenggara, Trainer &amp; Fasilitas
                    </h2>
                </div>
                <div class="form-section-body">
                    <div class="row g-4">
                        {{-- Tipe Penyelenggara (Radio Button: External / Internal) --}}
                        <div class="col-12">
                            <label class="form-label small fw-bold d-block">Tipe Penyelenggara <span class="text-danger">*</span></label>
                            <div class="radio-tile-group">
                                <label class="radio-tile-label">
                                    <input type="radio" name="tipe_penyelenggara" value="Internal" class="radio-tile-input" {{ old('tipe_penyelenggara', 'Internal') == 'Internal' ? 'checked' : '' }} onchange="togglePenyelenggaraType()">
                                    <div class="radio-tile-box">
                                        <i class="bi bi-building radio-tile-icon"></i>
                                        <div>
                                            <div class="fw-bold text-dark">Internal</div>
                                            <div class="small text-muted">Diselenggarakan internal Dharma Learning Center</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="radio-tile-label">
                                    <input type="radio" name="tipe_penyelenggara" value="External" class="radio-tile-input" {{ old('tipe_penyelenggara') == 'External' ? 'checked' : '' }} onchange="togglePenyelenggaraType()">
                                    <div class="radio-tile-box">
                                        <i class="bi bi-globe radio-tile-icon"></i>
                                        <div>
                                            <div class="fw-bold text-dark">External</div>
                                            <div class="small text-muted">Lembaga / Provider Pelatihan Eksternal</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Nama Penyelenggara --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Nama Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="nama_penyelenggara" id="inputNamaPenyelenggara" list="listProviders" class="form-control" value="{{ old('nama_penyelenggara', 'PT Dharma Polimetal Tbk (DLC)') }}" required placeholder="Contoh: PT Dharma Polimetal Tbk (DLC) / Nama Lembaga">
                            <datalist id="listProviders">
                                <option value="PT Dharma Polimetal Tbk (DLC)">
                                @foreach($providers as $p)
                                <option value="{{ $p->provider_name }}">
                                @endforeach
                            </datalist>
                            <div class="form-text small">Dapat dipilih dari Master Provider atau mengetik nama institusi penyelenggara.</div>
                        </div>

                        {{-- Trainer --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Trainer / Instruktur <span class="text-danger">*</span></label>
                            <input type="text" name="trainer" id="inputTrainer" list="listInstructors" class="form-control" value="{{ old('trainer') }}" required placeholder="Ketik nama trainer atau pilih instruktur...">
                            <datalist id="listInstructors">
                                @foreach($instructors as $inst)
                                <option value="{{ $inst->instructor_name }}">{{ $inst->instructor_name }} ({{ $inst->specialization ?? 'Instruktur' }})</option>
                                @endforeach
                            </datalist>
                            <div class="form-text small">Nama instruktur yang memandu pelatihan.</div>
                        </div>

                        {{-- Manager Class --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Manager Class</label>
                            <input type="text" name="manager_class" id="inputManagerClass" list="listStaffManagers" class="form-control" value="{{ old('manager_class') }}" placeholder="Pilih nama staff atau ketik PIC Manager Class...">
                            <datalist id="listStaffManagers">
                                @foreach($allStaff as $st)
                                <option value="{{ $st->nama_staff }}">{{ $st->nama_staff }} ({{ $st->divisi->nama_divisi ?? 'Staff' }})</option>
                                @endforeach
                            </datalist>
                            <div class="form-text small">Penanggung jawab / fasilitator teknis kelas pelatihan.</div>
                        </div>

                        {{-- Ruangan --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Ruangan Pelatihan</label>
                            <input type="text" name="ruangan" id="inputRuangan" list="listVenues" class="form-control" value="{{ old('ruangan') }}" placeholder="Pilih ruangan atau ketik nama venue...">
                            <datalist id="listVenues">
                                @foreach($venues as $v)
                                <option value="{{ $v->venue_name }}">{{ $v->venue_name }} ({{ $v->venue_type ?? 'Ruangan' }})</option>
                                @endforeach
                            </datalist>
                            <div class="form-text small">Ruang kelas atau lokasi diadakannya training.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. EVALUASI & TIPE SOAL --}}
            <div class="form-section-card">
                <div class="form-section-header">
                    <h2 class="form-section-title">
                        <i class="bi bi-file-earmark-check text-primary"></i> 3. Evaluasi &amp; Asesmen
                    </h2>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        {{-- Tipe Evaluasi --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Tipe Evaluasi</label>
                            <input type="text" name="tipe_evaluasi" list="listTipeEvaluasi" class="form-control" value="{{ old('tipe_evaluasi', 'Pre-Test & Post-Test') }}" placeholder="Pilih atau ketik tipe evaluasi...">
                            <datalist id="listTipeEvaluasi">
                                <option value="Pre-Test & Post-Test">
                                <option value="Level 1: Reaction (Kepuasan Pelatihan)">
                                <option value="Level 2: Learning (Ujian Pemahaman)">
                                <option value="Level 3: Behavior (Implementasi Pasca Training)">
                                <option value="Level 4: Results (Dampak Produktivitas / ROI)">
                                <option value="Kombinasi Level 1 & Level 2">
                            </datalist>
                            <div class="form-text small">Metode evaluasi keberhasilan pembelajaran.</div>
                        </div>

                        {{-- Tipe Soal --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Tipe Soal Asesmen</label>
                            <input type="text" name="tipe_soal" list="listTipeSoal" class="form-control" value="{{ old('tipe_soal', 'Pilihan Ganda') }}" placeholder="Pilih atau ketik tipe soal...">
                            <datalist id="listTipeSoal">
                                <option value="Pilihan Ganda (Multiple Choice)">
                                <option value="Essay / Uraian">
                                <option value="Praktek / Penilaian Simulasi">
                                <option value="Studi Kasus & Presentasi">
                                <option value="Kombinasi Pilihan Ganda & Essay">
                            </datalist>
                            <div class="form-text small">Bentuk format instrumen tes peserta.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. PESERTA TNA + ATASAN --}}
            <div class="form-section-card">
                <div class="form-section-header">
                    <div>
                        <h2 class="form-section-title">
                            <i class="bi bi-people-fill text-success"></i> 4. Peserta TNA + Atasan Langsung
                        </h2>
                        <div class="small text-muted">Daftar staf internal yang mengikuti program berdasarkan Training Need Analysis (TNA).</div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1" onclick="addRowPesertaTna()">
                        <i class="bi bi-plus-circle"></i> Tambah Baris Peserta TNA
                    </button>
                </div>
                <div class="form-section-body p-0">
                    <div class="table-responsive">
                        <table class="table dynamic-table mb-0" id="tablePesertaTna">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Pilih Staff (Internal)</th>
                                    <th style="width: 130px;">NPK</th>
                                    <th style="width: 170px;">Divisi / Bagian</th>
                                    <th>Atasan Langsung (Immediate Manager)</th>
                                    <th style="width: 60px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPesertaTna">
                                {{-- Rows added dynamically or 1 initial row --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light border-top text-muted small d-flex align-items-center justify-content-between">
                        <div>Total Peserta TNA Terinput: <strong class="text-success" id="counterPesertaTna">0</strong> Orang</div>
                        <div><i class="bi bi-info-circle me-1"></i>Ketika staf dipilih, NPK dan atasan langsung otomatis terisi.</div>
                    </div>
                </div>
            </div>

            {{-- 5. PESERTA NON-TNA + PIC SUBCO --}}
            <div class="form-section-card">
                <div class="form-section-header">
                    <div>
                        <h2 class="form-section-title">
                            <i class="bi bi-person-plus-fill text-warning"></i> 5. Peserta Non-TNA + PIC Subco
                        </h2>
                        <div class="small text-muted">Daftar peserta tambahan / peserta dari anak perusahaan (Subco) beserta PIC pengirim.</div>
                    </div>
                    <button type="button" class="btn btn-warning btn-sm d-inline-flex align-items-center gap-1" onclick="addRowPesertaNonTna()">
                        <i class="bi bi-plus-circle"></i> Tambah Baris Peserta Non-TNA
                    </button>
                </div>
                <div class="form-section-body p-0">
                    <div class="table-responsive">
                        <table class="table dynamic-table mb-0" id="tablePesertaNonTna">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Nama Peserta Non-TNA</th>
                                    <th style="width: 130px;">NPK</th>
                                    <th>Perusahaan Subco</th>
                                    <th>PIC Subco</th>
                                    <th style="width: 60px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPesertaNonTna">
                                {{-- Rows added dynamically --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light border-top text-muted small d-flex align-items-center justify-content-between">
                        <div>Total Peserta Non-TNA Terinput: <strong class="text-warning" id="counterPesertaNonTna">0</strong> Orang</div>
                        <div><i class="bi bi-info-circle me-1"></i>Isi nama peserta beserta perusahaan anak dan PIC yang mengutus.</div>
                    </div>
                </div>
            </div>

            {{-- 6. CATATAN & SUBMIT --}}
            <div class="form-section-card">
                <div class="form-section-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Tambahan</label>
                        <textarea name="catatan" rows="3" class="form-control" placeholder="Catatan opsional mengenai persiapan materi, konsumsi, link online meeting, dsb.">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('coe.training-event.index') }}" class="btn btn-secondary btn-sm px-3">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm px-4 py-2 fw-semibold">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Data Training Event
                        </button>
                    </div>
                </div>
            </div>

        </form>

    </div>
</main>

{{-- Subco Datalist for Autocomplete --}}
<datalist id="listSubco">
    <option value="PT. Dharma Polimetal Tbk">
    <option value="PT. Dharma Precision Parts">
    <option value="PT. Dharma Electrindo Manufacturing">
    <option value="PT. Dharma Controlcable Indonesia">
    <option value="PT. Trimitra Chitrahasta">
    <option value="PT. Sankei Dharma Indonesia">
    <option value="PT. Dharma Kyungshin Indonesia">
</datalist>

<script>
// Data staff for auto-filling Atasan and NPK
const staffDatabase = @json($allStaff);

// Event change on Select Kalender
function handleEventKalenderChange() {
    const select = document.getElementById('selectEventKalender');
    const box = document.getElementById('boxEventPreview');
    const opt = select.options[select.selectedIndex];

    if (opt.value) {
        document.getElementById('previewEventNama').textContent = opt.getAttribute('data-nama') + ' (' + opt.getAttribute('data-batch') + ')';
        document.getElementById('previewEventNo').textContent = opt.getAttribute('data-no');
        document.getElementById('previewEventTanggal').textContent = opt.getAttribute('data-tanggal-fmt');
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

// Toggle Penyelenggara Type
function togglePenyelenggaraType() {
    const isInternal = document.querySelector('input[name="tipe_penyelenggara"]:checked').value === 'Internal';
    const inputNama = document.getElementById('inputNamaPenyelenggara');

    if (isInternal) {
        if (!inputNama.value || inputNama.value.includes('Provider')) {
            inputNama.value = 'PT Dharma Polimetal Tbk (DLC)';
        }
    }
}

// ----------------- DYNAMIC TABLE: PESERTA TNA -----------------
let tnaIndex = 0;

function addRowPesertaTna(data = {}) {
    tnaIndex++;
    const tbody = document.getElementById('tbodyPesertaTna');
    const tr = document.createElement('tr');
    tr.id = `rowTna_${tnaIndex}`;

    // Options of staff
    let staffOptions = '<option value="">-- Pilih Staf Terdaftar --</option>';
    staffDatabase.forEach(st => {
        const mgrName = st.immediate_manager ? st.immediate_manager.nama_staff : (st.divisi ? st.divisi.nama_divisi + ' Manager' : '');
        const divName = st.divisi ? st.divisi.nama_divisi : (st.department ? st.department.nama_department : '');
        staffOptions += `<option value="${st.nama_staff}" data-npk="${st.npk_staff || ''}" data-divisi="${divName}" data-atasan="${mgrName}">${st.nama_staff} (${st.npk_staff || '-'})</option>`;
    });

    tr.innerHTML = `
        <td class="text-center fw-bold text-muted">${tbody.children.length + 1}</td>
        <td>
            <input type="text" name="peserta_tna[${tnaIndex}][nama]" list="listStaffSelect_${tnaIndex}" class="form-control form-control-sm" placeholder="Ketik atau pilih nama staf..." value="${data.nama || ''}" oninput="onStaffSelectChange(${tnaIndex})" id="inputStaffNama_${tnaIndex}">
            <datalist id="listStaffSelect_${tnaIndex}">
                ${staffOptions}
            </datalist>
        </td>
        <td>
            <input type="text" name="peserta_tna[${tnaIndex}][npk]" id="inputStaffNpk_${tnaIndex}" class="form-control form-control-sm" placeholder="NPK" value="${data.npk || ''}">
        </td>
        <td>
            <input type="text" name="peserta_tna[${tnaIndex}][divisi]" id="inputStaffDivisi_${tnaIndex}" class="form-control form-control-sm" placeholder="Divisi / Bagian" value="${data.divisi || ''}">
        </td>
        <td>
            <input type="text" name="peserta_tna[${tnaIndex}][atasan]" id="inputStaffAtasan_${tnaIndex}" class="form-control form-control-sm" placeholder="Nama Atasan Langsung" value="${data.atasan || ''}">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2" onclick="removeRowTna(${tnaIndex})" title="Hapus Baris">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateTnaCounters();
}

function onStaffSelectChange(idx) {
    const val = document.getElementById(`inputStaffNama_${idx}`).value.trim().toLowerCase();
    const matched = staffDatabase.find(s => s.nama_staff.toLowerCase() === val || (s.npk_staff && s.npk_staff.toLowerCase() === val));

    if (matched) {
        document.getElementById(`inputStaffNama_${idx}`).value = matched.nama_staff;
        document.getElementById(`inputStaffNpk_${idx}`).value = matched.npk_staff || '';
        document.getElementById(`inputStaffDivisi_${idx}`).value = matched.divisi ? matched.divisi.nama_divisi : (matched.department ? matched.department.nama_department : '');
        document.getElementById(`inputStaffAtasan_${idx}`).value = matched.immediate_manager ? matched.immediate_manager.nama_staff : '';
    }
    updateTnaCounters();
}

function removeRowTna(idx) {
    const row = document.getElementById(`rowTna_${idx}`);
    if (row) row.remove();
    reindexTable('tbodyPesertaTna');
    updateTnaCounters();
}

function updateTnaCounters() {
    const tbody = document.getElementById('tbodyPesertaTna');
    let filled = 0;
    tbody.querySelectorAll('input[id^="inputStaffNama_"]').forEach(inp => {
        if (inp.value.trim() !== '') filled++;
    });
    document.getElementById('counterPesertaTna').textContent = filled;
}

// ----------------- DYNAMIC TABLE: PESERTA NON-TNA -----------------
let nonTnaIndex = 0;

function addRowPesertaNonTna(data = {}) {
    nonTnaIndex++;
    const tbody = document.getElementById('tbodyPesertaNonTna');
    const tr = document.createElement('tr');
    tr.id = `rowNonTna_${nonTnaIndex}`;

    tr.innerHTML = `
        <td class="text-center fw-bold text-muted">${tbody.children.length + 1}</td>
        <td>
            <input type="text" name="peserta_non_tna[${nonTnaIndex}][nama]" class="form-control form-control-sm" placeholder="Nama lengkap peserta..." value="${data.nama || ''}" oninput="updateNonTnaCounters()" id="inputNonTnaNama_${nonTnaIndex}">
        </td>
        <td>
            <input type="text" name="peserta_non_tna[${nonTnaIndex}][npk]" class="form-control form-control-sm" placeholder="NPK" value="${data.npk || ''}">
        </td>
        <td>
            <input type="text" name="peserta_non_tna[${nonTnaIndex}][subco]" list="listSubco" class="form-control form-control-sm" placeholder="Pilih/ketik Subco..." value="${data.subco || ''}">
        </td>
        <td>
            <input type="text" name="peserta_non_tna[${nonTnaIndex}][pic_subco]" class="form-control form-control-sm" placeholder="Nama PIC Subco..." value="${data.pic_subco || ''}">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2" onclick="removeRowNonTna(${nonTnaIndex})" title="Hapus Baris">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateNonTnaCounters();
}

function removeRowNonTna(idx) {
    const row = document.getElementById(`rowNonTna_${idx}`);
    if (row) row.remove();
    reindexTable('tbodyPesertaNonTna');
    updateNonTnaCounters();
}

function updateNonTnaCounters() {
    const tbody = document.getElementById('tbodyPesertaNonTna');
    let filled = 0;
    tbody.querySelectorAll('input[id^="inputNonTnaNama_"]').forEach(inp => {
        if (inp.value.trim() !== '') filled++;
    });
    document.getElementById('counterPesertaNonTna').textContent = filled;
}

// Reindex row numbers
function reindexTable(tbodyId) {
    const tbody = document.getElementById(tbodyId);
    let count = 1;
    tbody.querySelectorAll('tr').forEach(tr => {
        const firstTd = tr.querySelector('td:first-child');
        if (firstTd) firstTd.textContent = count++;
    });
}

// Initial setup on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    handleEventKalenderChange();

    // Add 1 empty row for TNA and Non-TNA by default if empty
    if (document.getElementById('tbodyPesertaTna').children.length === 0) {
        addRowPesertaTna();
    }
    if (document.getElementById('tbodyPesertaNonTna').children.length === 0) {
        addRowPesertaNonTna();
    }
});
</script>
@endsection
