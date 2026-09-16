@extends('layouts.admindlc')

@section('title', 'Kalender of Event (COE) | Dharma Learning Center')

@section('content')
<style>
/* ========== CALENDAR STYLING ========== */
.coe-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
}

.stat-chip {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    transition: all 0.2s ease;
}
.stat-chip:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
}
.stat-chip-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

/* Calendar Grid */
.calendar-wrapper {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
}
.calendar-header-bar {
    padding: 1.25rem 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.calendar-nav-btn {
    border-radius: 8px;
    padding: 0.4rem 0.75rem;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.calendar-nav-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #0f172a;
}
.calendar-month-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    min-width: 200px;
    text-align: center;
}

.calendar-grid-header {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    text-align: center;
}
.calendar-grid-header .day-name {
    padding: 0.75rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #475569;
}
.calendar-grid-header .day-name.weekend {
    color: #dc2626;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    background: #e2e8f0;
    gap: 1px;
}
.calendar-day-cell {
    background: #ffffff;
    min-height: 125px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    position: relative;
    cursor: pointer;
    transition: background-color 0.15s ease, box-shadow 0.15s ease;
}
.calendar-day-cell:hover {
    background-color: #f8fafc;
    z-index: 2;
    box-shadow: inset 0 0 0 2px #3b82f6;
}
.calendar-day-cell.other-month {
    background-color: #fafbfc;
    color: #94a3b8;
    opacity: 0.65;
}
.calendar-day-cell.is-today {
    background-color: #f0fdf4;
}
.calendar-day-cell.is-today .day-number {
    background: #16a34a;
    color: #ffffff;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
.day-cell-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
}
.day-number {
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
}
.day-cell-top .badge-count {
    font-size: 0.68rem;
    padding: 2px 6px;
    border-radius: 10px;
    background: #e2e8f0;
    color: #475569;
    font-weight: 600;
}

/* Event Labels / Badges on calendar */
.events-container {
    display: flex;
    flex-direction: column;
    gap: 4px;
    overflow-y: auto;
    max-height: 95px;
}
.event-badge-label {
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-left: 3px solid transparent;
    transition: all 0.15s ease;
    cursor: pointer;
    display: block;
    line-height: 1.3;
}
.event-badge-label:hover {
    transform: scale(1.02);
    filter: brightness(0.95);
}

/* Color palettes for event badges */
.badge-evt-blue {
    background-color: #e0f2fe;
    color: #0369a1;
    border-left-color: #0284c7;
}
.badge-evt-emerald {
    background-color: #d1fae5;
    color: #065f46;
    border-left-color: #10b981;
}
.badge-evt-amber {
    background-color: #fef3c7;
    color: #92400e;
    border-left-color: #f59e0b;
}
.badge-evt-purple {
    background-color: #ede9fe;
    color: #5b21b6;
    border-left-color: #8b5cf6;
}
.badge-evt-rose {
    background-color: #ffe4e6;
    color: #9f1239;
    border-left-color: #f43f5e;
}
.badge-evt-indigo {
    background-color: #e0e7ff;
    color: #3730a3;
    border-left-color: #6366f1;
}

/* Tab Switcher */
.view-tab-btn {
    border: none;
    background: transparent;
    padding: 0.5rem 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #64748b;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
}
.view-tab-btn.active {
    color: #0284c7;
    border-bottom-color: #0284c7;
}

/* Table View */
.table-coe {
    width: 100%;
    margin-bottom: 0;
}
.table-coe thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 14px;
    border-bottom: 1.5px solid #e2e8f0;
}
.table-coe tbody td {
    padding: 12px 14px;
    vertical-align: middle;
    font-size: 0.875rem;
    border-bottom: 1px solid #f1f5f9;
}
.table-coe tbody tr:hover td {
    background: #f8fafc;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Hero Header --}}
        <div class="coe-card p-4 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Calendar Of Event</span>
                            <span class="text-muted small">Jadwal &amp; Agenda Training</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Kalender Event Pelatihan</h1>
                        <p class="text-muted mb-0 small">Rencana jadwal training per batch, alokasi kuota peserta (TNA &amp; Non-TNA), dan investasi biaya per peserta.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Event Kalender
                    </button>
                    <a href="{{ route('coe.training-event.create') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2">
                        <i class="bi bi-person-gear"></i> Konfigurasi Training Event
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <strong>Terdapat beberapa kesalahan pengisian:</strong>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Statistics Chips --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Agenda Bulan Ini</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalMonthEvents }} <span class="fs-6 fw-normal text-muted">Event</span></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Peserta TNA (Bulan Ini)</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalMonthPesertaTna }} <span class="fs-6 fw-normal text-muted">Orang</span></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Peserta Non-TNA (Bulan Ini)</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalMonthPesertaNonTna }} <span class="fs-6 fw-normal text-muted">Orang</span></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Investasi (Bulan Ini)</div>
                        <div class="fs-5 fw-bold text-dark">Rp {{ number_format($totalMonthBiaya, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- View Selector Tabs --}}
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <div class="d-flex gap-2">
                <button class="view-tab-btn active" id="btnTabCalendar" onclick="switchView('calendar')">
                    <i class="bi bi-calendar-month me-1"></i> Tampilan Kalender
                </button>
                <button class="view-tab-btn" id="btnTabTable" onclick="switchView('table')">
                    <i class="bi bi-table me-1"></i> Tampilan Tabel ({{ $totalAllEvents }})
                </button>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2 text-muted small">
                <!-- <span class="d-inline-flex align-items-center gap-1"><span class="badge bg-success p-1 rounded-circle"></span> Hari Ini</span> -->
                <span class="d-inline-flex align-items-center gap-1 ms-2"><i class="bi bi-info-circle text-primary"></i> Klik tanggal / label untuk melihat detail</span>
            </div>
        </div>

        {{-- ==================== VIEW 1: CALENDAR VIEW ==================== --}}
        <div id="viewCalendar">
            <div class="calendar-wrapper shadow-sm">
                {{-- Calendar Header Navigation --}}
                <div class="calendar-header-bar">
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $prevDate = $currentDate->copy()->subMonth();
                            $nextDate = $currentDate->copy()->addMonth();
                            $today = \Carbon\Carbon::now();
                        @endphp
                        <a href="{{ route('coe.kalender.index', ['year' => $prevDate->year, 'month' => $prevDate->month]) }}" class="calendar-nav-btn" title="Bulan Sebelumnya">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <a href="{{ route('coe.kalender.index', ['year' => $today->year, 'month' => $today->month]) }}" class="calendar-nav-btn" title="Kembali ke Hari Ini">
                            <i class="bi bi-dot text-success"></i> Hari Ini
                        </a>
                        <a href="{{ route('coe.kalender.index', ['year' => $nextDate->year, 'month' => $nextDate->month]) }}" class="calendar-nav-btn" title="Bulan Selanjutnya">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>

                    <div class="calendar-month-title">
                        {{ $currentDate->translatedFormat('F Y') }}
                    </div>

                    {{-- Quick Month / Year Selector --}}
                    <form action="{{ route('coe.kalender.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <select name="month" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                            @endfor
                        </select>
                        <select name="year" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
                            @for($y = $today->year - 3; $y <= $today->year + 3; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>

                {{-- Status Legend Bar --}}
                <div class="calendar-legend-bar d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-2 bg-light border-bottom small">
                    <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3">
                        <span class="fw-bold text-dark"><i class="bi bi-palette-fill text-primary me-1"></i>Status Pelatihan:</span>
                        <span class="d-inline-flex align-items-center gap-1">
                            <span class="badge badge-evt-blue py-1 px-2" style="font-size: 0.72rem;">Scheduled</span>
                            <!-- <span class="text-muted" style="font-size: 0.8rem;">Terjadwal</span> -->
                        </span>
                        <span class="d-inline-flex align-items-center gap-1">
                            <span class="badge badge-evt-amber py-1 px-2" style="font-size: 0.72rem;">Confirmed</span>
                            <!-- <span class="text-muted" style="font-size: 0.8rem;">Terkonfirmasi</span> -->
                        </span>
                        <span class="d-inline-flex align-items-center gap-1">
                            <span class="badge badge-evt-emerald py-1 px-2" style="font-size: 0.72rem;">Completed</span>
                            <!-- <span class="text-muted" style="font-size: 0.8rem;">Selesai</span> -->
                        </span>
                        <span class="d-inline-flex align-items-center gap-1">
                            <span class="badge badge-evt-purple py-1 px-2" style="font-size: 0.72rem;">Postponed</span>
                            <!-- <span class="text-muted" style="font-size: 0.8rem;">Ditunda</span> -->
                        </span>
                        <span class="d-inline-flex align-items-center gap-1">
                            <span class="badge badge-evt-rose py-1 px-2" style="font-size: 0.72rem;">Cancelled</span>
                            <!-- <span class="text-muted" style="font-size: 0.8rem;">Dibatalkan</span> -->
                        </span>
                    </div>
                    <div class="text-muted small d-none d-lg-block">
                        <i class="bi bi-info-circle me-1"></i>Warna label otomatis mengikuti status pelatihan
                    </div>
                </div>

                {{-- Weekday Names (Minggu - Sabtu) --}}
                <div class="calendar-grid-header">
                    <div class="day-name weekend">Minggu</div>
                    <div class="day-name">Senin</div>
                    <div class="day-name">Selasa</div>
                    <div class="day-name">Rabu</div>
                    <div class="day-name">Kamis</div>
                    <div class="day-name">Jumat</div>
                    <div class="day-name weekend">Sabtu</div>
                </div>

                {{-- Day Cells --}}
                <div class="calendar-grid">
                    @php
                        // Hitung hari awal kalender (0 = Minggu, 6 = Sabtu)
                        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 for Sunday
                        $daysInMonth = $currentDate->daysInMonth;

                        // Hari dari bulan sebelumnya untuk padding
                        $prevMonthDays = $currentDate->copy()->subMonth()->daysInMonth;

                        // Helper warna badge berdasarkan status pelatihan
                        $getStatusBadgeClass = function($status) {
                            return match(strtolower(trim($status ?? ''))) {
                                'confirmed' => 'badge-evt-amber',
                                'completed' => 'badge-evt-emerald',
                                'postponed' => 'badge-evt-purple',
                                'cancelled' => 'badge-evt-rose',
                                default => 'badge-evt-blue', // Scheduled
                            };
                        };
                    @endphp

                    {{-- Leading padding days from previous month --}}
                    @for($i = $startDayOfWeek - 1; $i >= 0; $i--)
                        @php
                            $paddingDayNum = $prevMonthDays - $i;
                            $paddingDate = $currentDate->copy()->subMonth()->day($paddingDayNum)->format('Y-m-d');
                            $paddingEvents = $eventsByDate[$paddingDate] ?? [];
                        @endphp
                        <div class="calendar-day-cell other-month" onclick="handleDateClick('{{ $paddingDate }}')">
                            <div class="day-cell-top">
                                <span class="day-number">{{ $paddingDayNum }}</span>
                                @if(count($paddingEvents) > 0)
                                <span class="badge-count">{{ count($paddingEvents) }}</span>
                                @endif
                            </div>
                            <div class="events-container">
                                @foreach($paddingEvents as $ev)
                                @php 
                                    $colorClass = $getStatusBadgeClass($ev['status'] ?? 'Scheduled'); 
                                    $isMulti = !empty($ev['is_multi_day']);
                                @endphp
                                <span class="event-badge-label {{ $colorClass }}" title="{{ $ev['nama_training'] }} ({{ $ev['batch_training'] }}) - [{{ $ev['date_range_display'] ?? '' }}] - [{{ $ev['status'] ?? 'Scheduled' }}]">
                                    @if($isMulti)
                                    <i class="bi bi-calendar2-range me-1" style="font-size: 0.62rem;" title="Multi-day: {{ $ev['date_range_display'] ?? '' }}"></i>
                                    @endif
                                    <strong>{{ $ev['batch_training'] }}:</strong> {{ $ev['nama_training'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    {{-- Current month days --}}
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $loopDate = Carbon\Carbon::create($year, $month, $day);
                            $dateStr = $loopDate->format('Y-m-d');
                            $isCurrentDay = $loopDate->isToday();
                            $dayEvents = $eventsByDate[$dateStr] ?? [];
                        @endphp
                        <div class="calendar-day-cell {{ $isCurrentDay ? 'is-today' : '' }}" onclick="handleDateClick('{{ $dateStr }}')">
                            <div class="day-cell-top">
                                <span class="day-number">{{ $day }}</span>
                                @if(count($dayEvents) > 0)
                                <span class="badge-count" title="{{ count($dayEvents) }} Event">{{ count($dayEvents) }}</span>
                                @endif
                            </div>

                            <div class="events-container">
                                @foreach($dayEvents as $ev)
                                @php 
                                    $colorClass = $getStatusBadgeClass($ev['status'] ?? 'Scheduled'); 
                                    $isMulti = !empty($ev['is_multi_day']);
                                    $rangeText = $ev['date_range_display'] ?? '';
                                @endphp
                                <span class="event-badge-label {{ $colorClass }}" 
                                      onclick="event.stopPropagation(); handleEventClick({{ $ev['id_event'] }}, '{{ $dateStr }}');"
                                      title="{{ $ev['no_event'] }} - {{ $ev['nama_training'] }} ({{ $ev['batch_training'] }}) [{{ $rangeText }}] - Status: {{ $ev['status'] ?? 'Scheduled' }} - {{ $ev['total_peserta'] }} Peserta">
                                    @if($isMulti)
                                    <i class="bi bi-calendar2-range me-1" style="font-size: 0.62rem;" title="Multi-day: {{ $rangeText }}"></i>
                                    @else
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>
                                    @endif
                                    <strong>{{ $ev['batch_training'] }}:</strong> {{ Str::limit($ev['nama_training'], 18) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    {{-- Trailing padding days for next month to complete the 7-day row --}}
                    @php
                        $totalCellsSoFar = $startDayOfWeek + $daysInMonth;
                        $remainingCells = (7 - ($totalCellsSoFar % 7)) % 7;
                    @endphp
                    @for($j = 1; $j <= $remainingCells; $j++)
                        @php
                            $nextDateStr = $currentDate->copy()->addMonth()->day($j)->format('Y-m-d');
                            $nextEvents = $eventsByDate[$nextDateStr] ?? [];
                        @endphp
                        <div class="calendar-day-cell other-month" onclick="handleDateClick('{{ $nextDateStr }}')">
                            <div class="day-cell-top">
                                <span class="day-number">{{ $j }}</span>
                                @if(count($nextEvents) > 0)
                                <span class="badge-count">{{ count($nextEvents) }}</span>
                                @endif
                            </div>
                            <div class="events-container">
                                @foreach($nextEvents as $ev)
                                @php 
                                    $colorClass = $getStatusBadgeClass($ev['status'] ?? 'Scheduled'); 
                                    $isMulti = !empty($ev['is_multi_day']);
                                @endphp
                                <span class="event-badge-label {{ $colorClass }}" title="{{ $ev['nama_training'] }} ({{ $ev['batch_training'] }}) - [{{ $ev['date_range_display'] ?? '' }}] - [{{ $ev['status'] ?? 'Scheduled' }}]">
                                    @if($isMulti)
                                    <i class="bi bi-calendar2-range me-1" style="font-size: 0.62rem;"></i>
                                    @endif
                                    <strong>{{ $ev['batch_training'] }}:</strong> {{ $ev['nama_training'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- ==================== VIEW 2: TABLE LIST VIEW ==================== --}}
        <div id="viewTable" style="display: none;">
            <div class="coe-card p-3 p-md-4">
                {{-- Filter & Search Bar --}}
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                    <form action="{{ route('coe.kalender.index') }}" method="GET" class="d-flex gap-2 flex-grow-1 max-w-lg" style="max-width: 450px;">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="month" value="{{ $month }}">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari No Event, Nama Training, Batch..." value="{{ request('search') }}">
                            @if(request('search'))
                            <a href="{{ route('coe.kalender.index', ['year' => $year, 'month' => $month]) }}" class="btn btn-outline-secondary">Reset</a>
                            @endif
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    <div class="text-muted small">
                        Total Data: <strong>{{ $allEvents->count() }}</strong> event
                    </div>
                </div>

                {{-- Table Content --}}
                <div class="table-responsive">
                    <table class="table table-coe">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Event</th>
                                <th>Nama Training</th>
                                <th>Batch</th>
                                <th>Tanggal Per Batch</th>
                                <th>Peserta TNA</th>
                                <th>Peserta Non-TNA</th>
                                <th>Total Peserta</th>
                                <th>Biaya / Orang</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Training Event</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allEvents as $idx => $event)
                            @php
                                $stKey = strtolower(trim($event->status ?? 'scheduled'));
                                $stBadge = match($stKey) {
                                    'confirmed' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                    'completed' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                    'postponed' => 'badge-evt-purple border',
                                    'cancelled' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                    default => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                };
                            @endphp
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace">{{ $event->no_event }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $event->nama_training }}</div>
                                    @if($event->catatan)
                                    <div class="text-muted small">{{ Str::limit($event->catatan, 35) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $event->batch_training }}</span>
                                </td>
                                <td>
                                    @if($event->is_multi_day)
                                    <div class="d-flex flex-column text-nowrap">
                                        <div class="d-flex align-items-center gap-1 text-primary fw-semibold">
                                            <i class="bi bi-calendar2-range"></i>
                                            <span>{{ $event->date_range_formatted }}</span>
                                        </div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 mt-1" style="font-size: 0.68rem; width: fit-content;">Multi-Day</span>
                                    </div>
                                    @else
                                    <div class="d-flex align-items-center gap-1 text-nowrap">
                                        <i class="bi bi-calendar-check text-muted"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->tanggal_per_batch)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    @endif
                                </td>
                                <td class="text-center fw-semibold text-success">{{ $event->jumlah_peserta_tna }}</td>
                                <td class="text-center fw-semibold text-warning">{{ $event->jumlah_peserta_non_tna }}</td>
                                <td class="text-center fw-bold">{{ $event->total_peserta }}</td>
                                <td>{{ $event->formatted_biaya }}</td>
                                <td class="fw-bold text-primary">{{ $event->formatted_total_biaya }}</td>
                                <td>
                                    <span class="badge {{ $stBadge }}">{{ $event->status ?? 'Scheduled' }}</span>
                                </td>
                                <td>
                                    @if($event->trainingEvent)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" title="Trainer: {{ $event->trainingEvent->trainer }}">
                                        <i class="bi bi-check2-circle"></i> Terisi
                                    </span>
                                    @else
                                    <a href="{{ route('coe.training-event.create', ['id_event' => $event->id_event]) }}" class="badge bg-secondary bg-opacity-10 text-secondary border text-decoration-none" title="Klik untuk melengkapi">
                                        <i class="bi bi-plus-circle"></i> Lengkapi
                                    </a>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-info p-1 px-2" onclick="handleEventClick({{ $event->id_event }}, '{{ $event->tanggal_per_batch->format('Y-m-d') }}')" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary p-1 px-2" onclick="openEditModal({{ json_encode($event) }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('coe.kalender.destroy', $event->id_event) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                    Belum ada data event pelatihan terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

{{-- ==================== MODAL 1: TAMBAH EVENT KALENDER ==================== --}}
<div class="modal fade" id="modalTambahEvent" tabindex="-1" aria-labelledby="modalTambahEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalTambahEventLabel">
                    <i class="bi bi-calendar-plus text-primary me-2"></i>Tambah Data Event Kalender
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('coe.kalender.store') }}" method="POST" id="formTambahEvent">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- No Event --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">No Event <span class="text-danger">*</span></label>
                            <input type="text" name="no_event" id="inputNoEvent" class="form-control" value="{{ old('no_event', $suggestedNoEvent) }}" required placeholder="Contoh: COE-2026-001">
                            <div class="form-text small">Nomor identifikasi unik event pelatihan.</div>
                        </div>

                        {{-- Batch Training --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Batch Training <span class="text-danger">*</span></label>
                            <input type="text" name="batch_training" id="inputBatchTraining" class="form-control" value="{{ old('batch_training', 'Batch 1') }}" required placeholder="Contoh: Batch 1, Batch 2">
                        </div>

                        {{-- Nama Training --}}
                        <div class="col-12">
                            <label class="form-label small fw-bold">Nama Training <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="nama_training" id="inputNamaTraining" list="listMasterTraining" class="form-control" value="{{ old('nama_training') }}" required placeholder="Pilih atau ketik nama topik training...">
                                <datalist id="listMasterTraining">
                                    @foreach($trainings as $t)
                                    <option value="{{ $t->nama_training }}">{{ $t->kode_training ? '['.$t->kode_training.'] ' : '' }}{{ $t->nama_training }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="form-text small">Dapat dipilih dari Master Training yang sudah ada atau mengetik kustom.</div>
                        </div>

                        {{-- Tanggal Per Batch --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Tanggal Per Batch <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_per_batch" id="inputTanggalPerBatch" class="form-control" value="{{ old('tanggal_per_batch', date('Y-m-d')) }}" required>
                        </div>

                        {{-- Tanggal Selesai (Opsional jika > 1 hari) --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Tanggal Selesai (Opsional)</label>
                            <input type="date" name="tanggal_selesai_batch" id="inputTanggalSelesaiBatch" class="form-control" value="{{ old('tanggal_selesai_batch') }}">
                            <div class="form-text small">Kosongkan jika pelatihan hanya berlangsung 1 hari.</div>
                        </div>

                        {{-- Jumlah Peserta TNA Per Batch --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Jumlah Peserta TNA Per Batch <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_peserta_tna" id="inputPesertaTna" class="form-control" min="0" value="{{ old('jumlah_peserta_tna', 0) }}" required oninput="calcTotals()">
                        </div>

                        {{-- Jumlah Peserta Non-TNA Per Batch --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Jumlah Peserta Non-TNA Per Batch <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_peserta_non_tna" id="inputPesertaNonTna" class="form-control" min="0" value="{{ old('jumlah_peserta_non_tna', 0) }}" required oninput="calcTotals()">
                        </div>

                        {{-- Biaya Investasi Perorang --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Biaya Investasi Perorang (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="biaya_investasi_perorang" id="inputBiayaPerorang" class="form-control" min="0" step="1000" value="{{ old('biaya_investasi_perorang', 0) }}" required oninput="calcTotals()">
                            </div>
                        </div>

                        {{-- Live Summary Calculation Box --}}
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small d-block">Total Peserta:</span>
                                    <strong class="fs-5 text-dark" id="displayTotalPeserta">0 Peserta</strong>
                                    <span class="text-muted small ms-1">(<span id="displayTnaCount">0</span> TNA + <span id="displayNonTnaCount">0</span> Non-TNA)</span>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted small d-block">Estimasi Total Biaya Investasi:</span>
                                    <strong class="fs-5 text-primary" id="displayTotalBiaya">Rp 0</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Status & Catatan --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Status Pelaksanaan</label>
                            <select name="status" class="form-select">
                                <option value="Scheduled" selected>Scheduled (Terjadwal)</option>
                                <option value="Confirmed">Confirmed (Terkonfirmasi)</option>
                                <option value="Completed">Completed (Selesai)</option>
                                <option value="Postponed">Postponed (Ditunda)</option>
                                <option value="Cancelled">Cancelled (Dibatalkan)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold">Catatan Tambahan</label>
                            <input type="text" name="catatan" class="form-control" placeholder="Opsional (ruangan sementara, ketentuan khusus, dll.)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Event Kalender
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== MODAL 2: DETAIL TANGGAL & EVENT ==================== --}}
<div class="modal fade" id="modalDetailTanggal" tabindex="-1" aria-labelledby="modalDetailTanggalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modalDetailTanggalLabel">
                        <i class="bi bi-calendar-date me-2"></i>Agenda Pada Tanggal: <span id="detailDateDisplay">-</span>
                    </h5>
                    <div class="small opacity-75" id="detailDateSubDisplay">Detail event pelatihan yang terjadwal</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalDetailContent">
                {{-- Dynamic event details will be injected here via JavaScript --}}
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnQuickAddOnDate">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Event Pada Tanggal Ini
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL 3: EDIT EVENT KALENDER ==================== --}}
<div class="modal fade" id="modalEditEvent" tabindex="-1" aria-labelledby="modalEditEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalEditEventLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Event Kalender
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formEditEvent">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">No Event <span class="text-danger">*</span></label>
                            <input type="text" name="no_event" id="editNoEvent" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Batch Training <span class="text-danger">*</span></label>
                            <input type="text" name="batch_training" id="editBatchTraining" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Nama Training <span class="text-danger">*</span></label>
                            <input type="text" name="nama_training" id="editNamaTraining" list="listMasterTraining" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Tanggal Per Batch <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_per_batch" id="editTanggalPerBatch" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Tanggal Selesai (Opsional)</label>
                            <input type="date" name="tanggal_selesai_batch" id="editTanggalSelesaiBatch" class="form-control">
                            <div class="form-text small">Kosongkan jika pelatihan hanya berlangsung 1 hari.</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Peserta TNA Per Batch <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_peserta_tna" id="editPesertaTna" class="form-control" min="0" required oninput="calcEditTotals()">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Peserta Non-TNA Per Batch <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_peserta_non_tna" id="editPesertaNonTna" class="form-control" min="0" required oninput="calcEditTotals()">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Biaya Investasi / Org <span class="text-danger">*</span></label>
                            <input type="number" name="biaya_investasi_perorang" id="editBiayaPerorang" class="form-control" min="0" step="1000" required oninput="calcEditTotals()">
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small d-block">Total Peserta:</span>
                                    <strong class="fs-5 text-dark" id="displayEditTotalPeserta">0 Peserta</strong>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted small d-block">Estimasi Total Biaya:</span>
                                    <strong class="fs-5 text-primary" id="displayEditTotalBiaya">Rp 0</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Status Pelaksanaan</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="Scheduled">Scheduled</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Completed">Completed</option>
                                <option value="Postponed">Postponed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold">Catatan</label>
                            <input type="text" name="catatan" id="editCatatan" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Pass events data from Blade to JavaScript --}}
<script>
const eventsByDate = @json($eventsByDate);
const deleteRouteBase = "{{ url('coe/kalender') }}";
const trainingEventCreateUrl = "{{ route('coe.training-event.create') }}";

// Function to switch between Calendar and Table view
function switchView(mode) {
    const calView = document.getElementById('viewCalendar');
    const tblView = document.getElementById('viewTable');
    const btnCal = document.getElementById('btnTabCalendar');
    const btnTbl = document.getElementById('btnTabTable');

    if (mode === 'table') {
        calView.style.display = 'none';
        tblView.style.display = 'block';
        btnCal.classList.remove('active');
        btnTbl.classList.add('active');
    } else {
        calView.style.display = 'block';
        tblView.style.display = 'none';
        btnCal.classList.add('active');
        btnTbl.classList.remove('active');
    }
}

// Live calculation for create modal
function calcTotals() {
    const tna = parseInt(document.getElementById('inputPesertaTna').value) || 0;
    const nonTna = parseInt(document.getElementById('inputPesertaNonTna').value) || 0;
    const biaya = parseFloat(document.getElementById('inputBiayaPerorang').value) || 0;

    const totalPeserta = tna + nonTna;
    const totalBiaya = totalPeserta * biaya;

    document.getElementById('displayTnaCount').textContent = tna;
    document.getElementById('displayNonTnaCount').textContent = nonTna;
    document.getElementById('displayTotalPeserta').textContent = totalPeserta + ' Peserta';
    document.getElementById('displayTotalBiaya').textContent = 'Rp ' + totalBiaya.toLocaleString('id-ID');
}

// Live calculation for edit modal
function calcEditTotals() {
    const tna = parseInt(document.getElementById('editPesertaTna').value) || 0;
    const nonTna = parseInt(document.getElementById('editPesertaNonTna').value) || 0;
    const biaya = parseFloat(document.getElementById('editBiayaPerorang').value) || 0;

    const totalPeserta = tna + nonTna;
    const totalBiaya = totalPeserta * biaya;

    document.getElementById('displayEditTotalPeserta').textContent = totalPeserta + ' Peserta';
    document.getElementById('displayEditTotalBiaya').textContent = 'Rp ' + totalBiaya.toLocaleString('id-ID');
}

// Format date to Indonesian localized format
function formatDateId(dateStr) {
    const parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    const d = new Date(parts[0], parts[1] - 1, parts[2]);
    return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

// Helper status badge with matching color
function getEventStatusBadge(status) {
    const s = (status || 'Scheduled').toLowerCase().trim();
    if (s === 'confirmed') {
        return '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i class="bi bi-check-circle me-1"></i>Confirmed</span>';
    } else if (s === 'completed') {
        return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-check-all me-1"></i>Completed</span>';
    } else if (s === 'postponed') {
        return '<span class="badge badge-evt-purple border py-0.5 px-2"><i class="bi bi-pause-circle me-1"></i>Postponed</span>';
    } else if (s === 'cancelled') {
        return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="bi bi-x-circle me-1"></i>Cancelled</span>';
    }
    return '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="bi bi-clock me-1"></i>Scheduled</span>';
}

// Handle date click on calendar
function handleDateClick(dateStr) {
    const events = eventsByDate[dateStr] || [];
    showDateDetailModal(dateStr, events);
}

// Handle individual event badge click on calendar
function handleEventClick(eventId, dateStr) {
    const events = eventsByDate[dateStr] || [];
    showDateDetailModal(dateStr, events, eventId);
}

// Render and show Date Detail Modal
function showDateDetailModal(dateStr, events, highlightEventId = null) {
    document.getElementById('detailDateDisplay').textContent = formatDateId(dateStr);
    const content = document.getElementById('modalDetailContent');
    const quickAddBtn = document.getElementById('btnQuickAddOnDate');

    quickAddBtn.onclick = function() {
        const modalDetail = bootstrap.Modal.getInstance(document.getElementById('modalDetailTanggal'));
        if (modalDetail) modalDetail.hide();

        document.getElementById('inputTanggalPerBatch').value = dateStr;
        const inputEnd = document.getElementById('inputTanggalSelesaiBatch');
        if (inputEnd) {
            inputEnd.value = '';
            inputEnd.min = dateStr;
        }

        calcTotals();
        const modalTambah = new bootstrap.Modal(document.getElementById('modalTambahEvent'));
        modalTambah.show();
    };

    if (!events || events.length === 0) {
        content.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="bi bi-calendar-x text-secondary" style="font-size: 2.5rem;"></i>
                <h6 class="mt-2 fw-bold text-dark">Belum ada agenda pelatihan</h6>
                <p class="small text-muted mb-3">Tidak ada jadwal training terdaftar pada tanggal <strong>${formatDateId(dateStr)}</strong>.</p>
                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('btnQuickAddOnDate').click()">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Event Baru Pada Tanggal Ini
                </button>
            </div>
        `;
    } else {
        let html = `<div class="d-flex flex-column gap-3">`;

        events.forEach((ev, idx) => {
            const isHighlighted = highlightEventId && ev.id_event === highlightEventId;
            const borderClass = isHighlighted ? 'border-primary shadow-sm' : 'border-light-subtle';

            let dateBadgeHtml = '';
            if (ev.is_multi_day) {
                dateBadgeHtml = `
                    <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-primary bg-opacity-10 rounded border border-primary border-opacity-25 text-primary small fw-semibold">
                        <i class="bi bi-calendar2-range"></i>
                        <span>Pelaksanaan: ${ev.date_range_display}</span>
                        <span class="badge bg-primary text-white ms-auto" style="font-size: 0.7rem;">Multi-Day</span>
                    </div>
                `;
            } else {
                dateBadgeHtml = `
                    <div class="d-flex align-items-center gap-1 mb-2 text-muted small">
                        <i class="bi bi-calendar-check"></i>
                        <span>Pelaksanaan: ${ev.date_range_display || ev.tanggal_formatted}</span>
                    </div>
                `;
            }

            let trainingEventHtml = '';
            if (ev.training_event) {
                const te = ev.training_event;
                const isInternal = te.tipe_penyelenggara === 'Internal';
                const badgeTipe = isInternal 
                    ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-building me-1"></i>Internal</span>'
                    : '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i class="bi bi-globe me-1"></i>External</span>';

                trainingEventHtml = `
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold small text-dark"><i class="bi bi-card-checklist text-primary me-1"></i>Detail Training Event:</span>
                            ${badgeTipe}
                        </div>
                        <div class="row g-2 small">
                            <div class="col-6 col-md-4">
                                <span class="text-muted d-block">Penyelenggara:</span>
                                <strong>${te.nama_penyelenggara || '-'}</strong>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted d-block">Trainer:</span>
                                <strong>${te.trainer || '-'}</strong>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted d-block">Manager Class:</span>
                                <strong>${te.manager_class || '-'}</strong>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted d-block">Ruangan:</span>
                                <strong>${te.ruangan || '-'}</strong>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted d-block">Tipe Evaluasi:</span>
                                <strong>${te.tipe_evaluasi || '-'}</strong>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted d-block">Tipe Soal:</span>
                                <strong>${te.tipe_soal || '-'}</strong>
                            </div>
                        </div>

                        <!-- Peserta TNA & Non-TNA Preview -->
                        <div class="mt-2 pt-2 d-flex gap-2">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-people me-1"></i>Peserta TNA Terdata: ${te.total_peserta_tna_detail || 0}
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-people me-1"></i>Peserta Non-TNA: ${te.total_peserta_non_tna_detail || 0}
                            </span>
                        </div>
                    </div>
                `;
            } else {
                trainingEventHtml = `
                    <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Detail Training Event belum dikonfigurasi.</span>
                        <a href="${trainingEventCreateUrl}?id_event=${ev.id_event}" class="btn btn-outline-primary btn-sm py-1 px-2" style="font-size: 0.78rem;">
                            <i class="bi bi-plus-circle me-1"></i>Lengkapi Training Event
                        </a>
                    </div>
                `;
            }

            html += `
                <div class="card ${borderClass} rounded-3 p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 font-monospace">${ev.no_event}</span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border">${ev.batch_training}</span>
                            ${getEventStatusBadge(ev.status)}
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-primary btn-sm py-0.5 px-2" onclick='openEditModal(${JSON.stringify(ev)})' title="Edit Event Kalender">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <form action="${deleteRouteBase}/${ev.id_event}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus event [${ev.no_event}]?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger btn-sm py-0.5 px-2" title="Hapus Event">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">${ev.nama_training}</h5>
                    ${dateBadgeHtml}
                    ${ev.catatan ? `<div class="text-muted small mb-2"><i class="bi bi-card-text me-1"></i>${ev.catatan}</div>` : ''}

                    <div class="row g-2 mt-1">
                        <div class="col-6 col-md-3">
                            <div class="p-2 bg-light rounded text-center">
                                <span class="text-muted d-block small" style="font-size: 0.72rem;">PESERTA TNA</span>
                                <strong class="fs-6 text-success">${ev.jumlah_peserta_tna}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 bg-light rounded text-center">
                                <span class="text-muted d-block small" style="font-size: 0.72rem;">PESERTA NON-TNA</span>
                                <strong class="fs-6 text-warning">${ev.jumlah_peserta_non_tna}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 bg-light rounded text-center">
                                <span class="text-muted d-block small" style="font-size: 0.72rem;">TOTAL PESERTA</span>
                                <strong class="fs-6 text-dark">${ev.total_peserta}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 bg-light rounded text-center">
                                <span class="text-muted d-block small" style="font-size: 0.72rem;">TOTAL ESTIMASI</span>
                                <strong class="fs-6 text-primary" style="font-size: 0.85rem !important;">${ev.total_biaya_formatted}</strong>
                            </div>
                        </div>
                    </div>

                    ${trainingEventHtml}
                </div>
            `;
        });

        html += `</div>`;
        content.innerHTML = html;
    }

    const modal = new bootstrap.Modal(document.getElementById('modalDetailTanggal'));
    modal.show();
}

// Open Edit Modal and fill existing event data
function openEditModal(eventData) {
    // Hide detail modal if open
    const modalDetail = bootstrap.Modal.getInstance(document.getElementById('modalDetailTanggal'));
    if (modalDetail) modalDetail.hide();

    const form = document.getElementById('formEditEvent');
    form.action = `${deleteRouteBase}/${eventData.id_event}`;

    document.getElementById('editNoEvent').value = eventData.no_event;
    document.getElementById('editBatchTraining').value = eventData.batch_training;
    document.getElementById('editNamaTraining').value = eventData.nama_training;

    const startDate = eventData.tanggal_per_batch ? eventData.tanggal_per_batch.substring(0, 10) : '';
    document.getElementById('editTanggalPerBatch').value = startDate;

    const editEndInput = document.getElementById('editTanggalSelesaiBatch');
    const endDate = eventData.tanggal_selesai_batch ? eventData.tanggal_selesai_batch.substring(0, 10) : '';
    if (endDate && endDate !== startDate) {
        editEndInput.value = endDate;
    } else {
        editEndInput.value = '';
    }
    editEndInput.min = startDate;

    document.getElementById('editPesertaTna').value = eventData.jumlah_peserta_tna;
    document.getElementById('editPesertaNonTna').value = eventData.jumlah_peserta_non_tna;
    document.getElementById('editBiayaPerorang').value = eventData.biaya_investasi_perorang;
    document.getElementById('editStatus').value = eventData.status || 'Scheduled';
    document.getElementById('editCatatan').value = eventData.catatan || '';

    calcEditTotals();

    const modalEdit = new bootstrap.Modal(document.getElementById('modalEditEvent'));
    modalEdit.show();
}

// Initial calc and event listeners when modal loads
document.addEventListener('DOMContentLoaded', function() {
    calcTotals();

    // Date range min constraint syncing for create modal
    const inputStart = document.getElementById('inputTanggalPerBatch');
    const inputEnd = document.getElementById('inputTanggalSelesaiBatch');
    if (inputStart && inputEnd) {
        if (inputStart.value) {
            inputEnd.min = inputStart.value;
        }
        inputStart.addEventListener('change', function() {
            inputEnd.min = this.value;
            if (inputEnd.value && inputEnd.value < this.value) {
                inputEnd.value = '';
            }
        });
    }

    // Date range min constraint syncing for edit modal
    const editStart = document.getElementById('editTanggalPerBatch');
    const editEnd = document.getElementById('editTanggalSelesaiBatch');
    if (editStart && editEnd) {
        if (editStart.value) {
            editEnd.min = editStart.value;
        }
        editStart.addEventListener('change', function() {
            editEnd.min = this.value;
            if (editEnd.value && editEnd.value < this.value) {
                editEnd.value = '';
            }
        });
    }
});
</script>
@endsection
