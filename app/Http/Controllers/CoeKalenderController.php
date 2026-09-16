<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoeEvent;
use App\Models\CoeTrainingEvent;
use App\Models\TrainingModel;
use App\Models\ProviderModel;
use App\Models\InstructorModel;
use App\Models\VenueModel;
use App\Models\StaffModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CoeKalenderController extends Controller
{
    /**
     * Display the Calendar Of Event index page (Calendar view)
     */
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        // Validasi bulan dan tahun
        $year = (int) $year;
        $month = (int) $month;
        if ($month < 1 || $month > 12) {
            $month = Carbon::now()->month;
        }

        $currentDate = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Query events for current month (and all events for the year list)
        $eventsQuery = CoeEvent::with(['training', 'trainingEvent'])
            ->orderBy('tanggal_per_batch', 'asc');

        // Filter pencarian jika ada
        if ($request->filled('search')) {
            $search = trim($request->search);
            $eventsQuery->where(function ($q) use ($search) {
                $q->where('no_event', 'like', "%{$search}%")
                  ->orWhere('nama_training', 'like', "%{$search}%")
                  ->orWhere('batch_training', 'like', "%{$search}%");
            });
        }

        $allEvents = (clone $eventsQuery)->get();

        // Event khusus bulan yang aktif (beririsan dengan bulan ini)
        $monthEvents = $allEvents->filter(function ($event) use ($startOfMonth, $endOfMonth) {
            $startDate = Carbon::parse($event->tanggal_per_batch)->startOfDay();
            $endDate = ($event->tanggal_selesai_batch && Carbon::parse($event->tanggal_selesai_batch)->gte($startDate))
                ? Carbon::parse($event->tanggal_selesai_batch)->startOfDay()
                : $startDate;
            return $startDate->lte($endOfMonth) && $endDate->gte($startOfMonth);
        });

        // Group events by date string (Y-m-d) for easy calendar lookup
        $eventsByDate = [];
        foreach ($allEvents as $ev) {
            $startDate = Carbon::parse($ev->tanggal_per_batch)->startOfDay();
            $endDate = ($ev->tanggal_selesai_batch && Carbon::parse($ev->tanggal_selesai_batch)->gte($startDate))
                ? Carbon::parse($ev->tanggal_selesai_batch)->startOfDay()
                : $startDate->copy();

            $isMultiDay = $endDate->gt($startDate);
            $dateRangeDisplay = $isMultiDay
                ? $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y')
                : $startDate->translatedFormat('d F Y');

            $eventPayload = [
                'id_event' => $ev->id_event,
                'no_event' => $ev->no_event,
                'nama_training' => $ev->nama_training,
                'batch_training' => $ev->batch_training,
                'tanggal_per_batch' => $startDate->format('Y-m-d'),
                'tanggal_selesai_batch' => $ev->tanggal_selesai_batch ? Carbon::parse($ev->tanggal_selesai_batch)->format('Y-m-d') : null,
                'tanggal_formatted' => $startDate->translatedFormat('d F Y'),
                'tanggal_selesai_formatted' => $ev->tanggal_selesai_batch ? Carbon::parse($ev->tanggal_selesai_batch)->translatedFormat('d F Y') : null,
                'is_multi_day' => $isMultiDay,
                'date_range_display' => $dateRangeDisplay,
                'jumlah_peserta_tna' => $ev->jumlah_peserta_tna,
                'jumlah_peserta_non_tna' => $ev->jumlah_peserta_non_tna,
                'total_peserta' => $ev->total_peserta,
                'biaya_investasi_perorang' => (float) $ev->biaya_investasi_perorang,
                'biaya_formatted' => $ev->formatted_biaya,
                'total_biaya' => (float) $ev->total_biaya,
                'total_biaya_formatted' => $ev->formatted_total_biaya,
                'status' => $ev->status,
                'catatan' => $ev->catatan,
                'has_training_detail' => $ev->trainingEvent ? true : false,
                'training_event' => $ev->trainingEvent ? [
                    'id_training_event' => $ev->trainingEvent->id_training_event,
                    'tipe_penyelenggara' => $ev->trainingEvent->tipe_penyelenggara,
                    'nama_penyelenggara' => $ev->trainingEvent->nama_penyelenggara,
                    'trainer' => $ev->trainingEvent->trainer,
                    'manager_class' => $ev->trainingEvent->manager_class,
                    'tipe_evaluasi' => $ev->trainingEvent->tipe_evaluasi,
                    'tipe_soal' => $ev->trainingEvent->tipe_soal,
                    'ruangan' => $ev->trainingEvent->ruangan,
                    'total_peserta_tna_detail' => $ev->trainingEvent->total_peserta_tna,
                    'total_peserta_non_tna_detail' => $ev->trainingEvent->total_peserta_non_tna,
                    'peserta_tna' => $ev->trainingEvent->peserta_tna ?? [],
                    'peserta_non_tna' => $ev->trainingEvent->peserta_non_tna ?? [],
                ] : null,
            ];

            // Masukkan event ke setiap tanggal dalam rentang tanggal_per_batch s/d tanggal_selesai_batch
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $dt) {
                $dateKey = $dt->format('Y-m-d');
                if (!isset($eventsByDate[$dateKey])) {
                    $eventsByDate[$dateKey] = [];
                }
                $eventsByDate[$dateKey][] = $eventPayload;
            }
        }

        // Statistik bulan ini
        $totalMonthEvents = $monthEvents->count();
        $totalMonthPesertaTna = $monthEvents->sum('jumlah_peserta_tna');
        $totalMonthPesertaNonTna = $monthEvents->sum('jumlah_peserta_non_tna');
        $totalMonthPeserta = $totalMonthPesertaTna + $totalMonthPesertaNonTna;
        $totalMonthBiaya = $monthEvents->sum('total_biaya');

        // Statistik keseluruhan (all events)
        $totalAllEvents = $allEvents->count();
        $totalAllPeserta = $allEvents->sum('total_peserta');
        $totalAllBiaya = $allEvents->sum('total_biaya');

        // Master data untuk dropdown modal input
        $trainings = TrainingModel::orderBy('nama_training', 'asc')->get();
        $suggestedNoEvent = CoeEvent::generateNoEvent();

        return view('dlc.coe.kalender.index', compact(
            'year',
            'month',
            'currentDate',
            'startOfMonth',
            'endOfMonth',
            'allEvents',
            'monthEvents',
            'eventsByDate',
            'totalMonthEvents',
            'totalMonthPesertaTna',
            'totalMonthPesertaNonTna',
            'totalMonthPeserta',
            'totalMonthBiaya',
            'totalAllEvents',
            'totalAllPeserta',
            'totalAllBiaya',
            'trainings',
            'suggestedNoEvent'
        ));
    }

    /**
     * API JSON endpoint for dynamic month changes or event feed
     */
    public function eventsJson(Request $request)
    {
        $events = CoeEvent::with(['training', 'trainingEvent'])
            ->orderBy('tanggal_per_batch', 'asc')
            ->get()
            ->map(function ($ev) {
                return [
                    'id_event' => $ev->id_event,
                    'no_event' => $ev->no_event,
                    'nama_training' => $ev->nama_training,
                    'batch_training' => $ev->batch_training,
                    'tanggal_per_batch' => $ev->tanggal_per_batch ? Carbon::parse($ev->tanggal_per_batch)->format('Y-m-d') : null,
                    'tanggal_selesai_batch' => $ev->tanggal_selesai_batch ? Carbon::parse($ev->tanggal_selesai_batch)->format('Y-m-d') : null,
                    'tanggal_formatted' => $ev->tanggal_per_batch ? Carbon::parse($ev->tanggal_per_batch)->translatedFormat('d F Y') : null,
                    'tanggal_selesai_formatted' => $ev->tanggal_selesai_batch ? Carbon::parse($ev->tanggal_selesai_batch)->translatedFormat('d F Y') : null,
                    'is_multi_day' => $ev->is_multi_day,
                    'date_range_display' => $ev->date_range_formatted,
                    'jumlah_peserta_tna' => $ev->jumlah_peserta_tna,
                    'jumlah_peserta_non_tna' => $ev->jumlah_peserta_non_tna,
                    'total_peserta' => $ev->total_peserta,
                    'biaya_investasi_perorang' => (float) $ev->biaya_investasi_perorang,
                    'biaya_formatted' => $ev->formatted_biaya,
                    'total_biaya' => (float) $ev->total_biaya,
                    'total_biaya_formatted' => $ev->formatted_total_biaya,
                    'status' => $ev->status,
                    'catatan' => $ev->catatan,
                    'has_training_detail' => $ev->trainingEvent ? true : false,
                    'training_event' => $ev->trainingEvent,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $events,
        ]);
    }

    /**
     * Store new calendar event
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_event' => 'required|string|max:50|unique:coe_events,no_event',
            'nama_training' => 'required|string|max:255',
            'batch_training' => 'required|string|max:100',
            'tanggal_per_batch' => 'required|date',
            'tanggal_selesai_batch' => 'nullable|date|after_or_equal:tanggal_per_batch',
            'jumlah_peserta_tna' => 'required|integer|min:0',
            'jumlah_peserta_non_tna' => 'required|integer|min:0',
            'biaya_investasi_perorang' => 'required|numeric|min:0',
        ], [
            'no_event.required' => 'No Event wajib diisi.',
            'no_event.unique' => 'No Event sudah digunakan, silakan gunakan nomor lain.',
            'nama_training.required' => 'Nama Training wajib diisi.',
            'batch_training.required' => 'Batch Training wajib diisi.',
            'tanggal_per_batch.required' => 'Tanggal Per batch wajib diisi.',
            'tanggal_selesai_batch.after_or_equal' => 'Tanggal Selesai tidak boleh lebih awal dari Tanggal Per Batch.',
            'jumlah_peserta_tna.required' => 'Jumlah Peserta TNA wajib diisi.',
            'jumlah_peserta_non_tna.required' => 'Jumlah Peserta Non-TNA wajib diisi.',
            'biaya_investasi_perorang.required' => 'Biaya Investasi Perorang wajib diisi.',
        ]);

        $pesertaTna = (int) $request->input('jumlah_peserta_tna', 0);
        $pesertaNonTna = (int) $request->input('jumlah_peserta_non_tna', 0);
        $biayaPerOrang = (float) $request->input('biaya_investasi_perorang', 0);
        $totalBiaya = ($pesertaTna + $pesertaNonTna) * $biayaPerOrang;

        $tglPerBatch = Carbon::parse($request->tanggal_per_batch)->format('Y-m-d');
        $tglSelesaiBatch = null;
        if ($request->filled('tanggal_selesai_batch')) {
            $parsedSelesai = Carbon::parse($request->tanggal_selesai_batch)->format('Y-m-d');
            if ($parsedSelesai > $tglPerBatch) {
                $tglSelesaiBatch = $parsedSelesai;
            }
        }

        $event = CoeEvent::create([
            'no_event' => trim($request->no_event),
            'id_training' => $request->filled('id_training') ? (int) $request->id_training : null,
            'nama_training' => trim($request->nama_training),
            'batch_training' => trim($request->batch_training),
            'tanggal_per_batch' => $tglPerBatch,
            'tanggal_selesai_batch' => $tglSelesaiBatch,
            'jumlah_peserta_tna' => $pesertaTna,
            'jumlah_peserta_non_tna' => $pesertaNonTna,
            'biaya_investasi_perorang' => $biayaPerOrang,
            'total_biaya' => $totalBiaya,
            'status' => $request->filled('status') ? $request->status : 'Scheduled',
            'catatan' => $request->catatan,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event Kalender berhasil disimpan!',
                'data' => $event,
            ]);
        }

        return redirect()->route('coe.kalender.index', [
            'year' => Carbon::parse($event->tanggal_per_batch)->year,
            'month' => Carbon::parse($event->tanggal_per_batch)->month,
        ])->with('success', "Event [{$event->no_event}] {$event->nama_training} berhasil ditambahkan ke kalender.");
    }

    /**
     * Show detail of an event
     */
    public function show($id)
    {
        $event = CoeEvent::with(['training', 'trainingEvent'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id_event' => $event->id_event,
                    'no_event' => $event->no_event,
                    'nama_training' => $event->nama_training,
                    'batch_training' => $event->batch_training,
                    'tanggal_per_batch' => $event->tanggal_per_batch ? Carbon::parse($event->tanggal_per_batch)->format('Y-m-d') : null,
                    'tanggal_selesai_batch' => $event->tanggal_selesai_batch ? Carbon::parse($event->tanggal_selesai_batch)->format('Y-m-d') : null,
                    'tanggal_formatted' => $event->tanggal_per_batch ? Carbon::parse($event->tanggal_per_batch)->translatedFormat('d F Y') : null,
                    'tanggal_selesai_formatted' => $event->tanggal_selesai_batch ? Carbon::parse($event->tanggal_selesai_batch)->translatedFormat('d F Y') : null,
                    'is_multi_day' => $event->is_multi_day,
                    'date_range_display' => $event->date_range_formatted,
                    'jumlah_peserta_tna' => $event->jumlah_peserta_tna,
                    'jumlah_peserta_non_tna' => $event->jumlah_peserta_non_tna,
                    'total_peserta' => $event->total_peserta,
                    'biaya_investasi_perorang' => (float) $event->biaya_investasi_perorang,
                    'biaya_formatted' => $event->formatted_biaya,
                    'total_biaya' => (float) $event->total_biaya,
                    'total_biaya_formatted' => $event->formatted_total_biaya,
                    'status' => $event->status,
                    'catatan' => $event->catatan,
                    'training_event' => $event->trainingEvent,
                ]
            ]);
        }

        return redirect()->route('coe.kalender.index');
    }

    /**
     * Update an event
     */
    public function update(Request $request, $id)
    {
        $event = CoeEvent::findOrFail($id);

        $request->validate([
            'no_event' => 'required|string|max:50|unique:coe_events,no_event,' . $id . ',id_event',
            'nama_training' => 'required|string|max:255',
            'batch_training' => 'required|string|max:100',
            'tanggal_per_batch' => 'required|date',
            'tanggal_selesai_batch' => 'nullable|date|after_or_equal:tanggal_per_batch',
            'jumlah_peserta_tna' => 'required|integer|min:0',
            'jumlah_peserta_non_tna' => 'required|integer|min:0',
            'biaya_investasi_perorang' => 'required|numeric|min:0',
        ], [
            'no_event.required' => 'No Event wajib diisi.',
            'no_event.unique' => 'No Event sudah digunakan, silakan gunakan nomor lain.',
            'nama_training.required' => 'Nama Training wajib diisi.',
            'batch_training.required' => 'Batch Training wajib diisi.',
            'tanggal_per_batch.required' => 'Tanggal Per batch wajib diisi.',
            'tanggal_selesai_batch.after_or_equal' => 'Tanggal Selesai tidak boleh lebih awal dari Tanggal Per Batch.',
            'jumlah_peserta_tna.required' => 'Jumlah Peserta TNA wajib diisi.',
            'jumlah_peserta_non_tna.required' => 'Jumlah Peserta Non-TNA wajib diisi.',
            'biaya_investasi_perorang.required' => 'Biaya Investasi Perorang wajib diisi.',
        ]);

        $pesertaTna = (int) $request->input('jumlah_peserta_tna', 0);
        $pesertaNonTna = (int) $request->input('jumlah_peserta_non_tna', 0);
        $biayaPerOrang = (float) $request->input('biaya_investasi_perorang', 0);
        $totalBiaya = ($pesertaTna + $pesertaNonTna) * $biayaPerOrang;

        $tglPerBatch = Carbon::parse($request->tanggal_per_batch)->format('Y-m-d');
        $tglSelesaiBatch = null;
        if ($request->filled('tanggal_selesai_batch')) {
            $parsedSelesai = Carbon::parse($request->tanggal_selesai_batch)->format('Y-m-d');
            if ($parsedSelesai > $tglPerBatch) {
                $tglSelesaiBatch = $parsedSelesai;
            }
        }

        $event->update([
            'no_event' => trim($request->no_event),
            'id_training' => $request->filled('id_training') ? (int) $request->id_training : null,
            'nama_training' => trim($request->nama_training),
            'batch_training' => trim($request->batch_training),
            'tanggal_per_batch' => $tglPerBatch,
            'tanggal_selesai_batch' => $tglSelesaiBatch,
            'jumlah_peserta_tna' => $pesertaTna,
            'jumlah_peserta_non_tna' => $pesertaNonTna,
            'biaya_investasi_perorang' => $biayaPerOrang,
            'total_biaya' => $totalBiaya,
            'status' => $request->filled('status') ? $request->status : $event->status,
            'catatan' => $request->catatan,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event Kalender berhasil diperbarui!',
                'data' => $event,
            ]);
        }

        return redirect()->back()->with('success', "Event [{$event->no_event}] berhasil diperbarui.");
    }

    /**
     * Delete an event
     */
    public function destroy($id)
    {
        $event = CoeEvent::findOrFail($id);
        $noEvent = $event->no_event;
        $event->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Event {$noEvent} berhasil dihapus.",
            ]);
        }

        return redirect()->back()->with('success', "Event {$noEvent} berhasil dihapus.");
    }
}
