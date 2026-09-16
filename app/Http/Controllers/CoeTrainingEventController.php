<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoeEvent;
use App\Models\CoeTrainingEvent;
use App\Models\ProviderModel;
use App\Models\InstructorModel;
use App\Models\VenueModel;
use App\Models\StaffModel;
use App\Models\TrainingModel;

class CoeTrainingEventController extends Controller
{
    /**
     * Display a listing of Training Events
     */
    public function index(Request $request)
    {
        $query = CoeTrainingEvent::with('coeEvent');

        if ($request->filled('tipe')) {
            $query->where('tipe_penyelenggara', $request->tipe);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_penyelenggara', 'like', "%{$search}%")
                  ->orWhere('trainer', 'like', "%{$search}%")
                  ->orWhere('manager_class', 'like', "%{$search}%")
                  ->orWhere('ruangan', 'like', "%{$search}%")
                  ->orWhere('tipe_evaluasi', 'like', "%{$search}%")
                  ->orWhere('tipe_soal', 'like', "%{$search}%")
                  ->orWhereHas('coeEvent', function ($sub) use ($search) {
                      $sub->where('nama_training', 'like', "%{$search}%")
                          ->orWhere('no_event', 'like', "%{$search}%")
                          ->orWhere('batch_training', 'like', "%{$search}%");
                  });
            });
        }

        $trainingEvents = $query->orderBy('id_training_event', 'desc')->paginate(15)->withQueryString();

        // Metrics
        $totalCount = CoeTrainingEvent::count();
        $internalCount = CoeTrainingEvent::where('tipe_penyelenggara', 'Internal')->count();
        $externalCount = CoeTrainingEvent::where('tipe_penyelenggara', 'External')->count();

        return view('dlc.coe.training-event.index', compact(
            'trainingEvents',
            'totalCount',
            'internalCount',
            'externalCount'
        ));
    }

    /**
     * Show the form for creating a new Training Event
     */
    public function create(Request $request)
    {
        $selectedEventId = $request->get('id_event');
        $selectedEvent = null;
        if ($selectedEventId) {
            $selectedEvent = CoeEvent::find($selectedEventId);
        }

        // List event kalender yang belum memiliki training event (atau event yang dipilih)
        $allEvents = CoeEvent::orderBy('tanggal_per_batch', 'desc')->get();
        $providers = ProviderModel::orderBy('provider_name')->get();
        $instructors = InstructorModel::orderBy('instructor_name')->get();
        $venues = VenueModel::orderBy('venue_name')->get();
        $allStaff = StaffModel::with(['divisi', 'department', 'levelJabatan', 'immediateManager'])
            ->orderBy('nama_staff', 'asc')
            ->get();

        return view('dlc.coe.training-event.create', compact(
            'allEvents',
            'selectedEvent',
            'selectedEventId',
            'providers',
            'instructors',
            'venues',
            'allStaff'
        ));
    }

    /**
     * Store a newly created Training Event
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe_penyelenggara' => 'required|in:Internal,External',
            'nama_penyelenggara' => 'required|string|max:255',
            'trainer'            => 'required|string|max:255',
            'manager_class'      => 'nullable|string|max:255',
            'tipe_evaluasi'      => 'nullable|string|max:255',
            'tipe_soal'          => 'nullable|string|max:255',
            'ruangan'            => 'nullable|string|max:255',
            'id_event'           => 'nullable|exists:coe_events,id_event',
        ], [
            'tipe_penyelenggara.required' => 'Tipe penyelenggara wajib dipilih.',
            'nama_penyelenggara.required' => 'Nama penyelenggara wajib diisi.',
            'trainer.required'            => 'Nama trainer wajib diisi.',
        ]);

        // Filter and sanitize Peserta TNA
        $pesertaTna = [];
        if ($request->has('peserta_tna') && is_array($request->peserta_tna)) {
            foreach ($request->peserta_tna as $row) {
                if (!empty($row['nama']) || !empty($row['npk'])) {
                    $pesertaTna[] = [
                        'npk'      => trim($row['npk'] ?? ''),
                        'nama'     => trim($row['nama'] ?? ''),
                        'jabatan'  => trim($row['jabatan'] ?? ''),
                        'divisi'   => trim($row['divisi'] ?? ''),
                        'atasan'   => trim($row['atasan'] ?? ''),
                    ];
                }
            }
        }

        // Filter and sanitize Peserta Non-TNA
        $pesertaNonTna = [];
        if ($request->has('peserta_non_tna') && is_array($request->peserta_non_tna)) {
            foreach ($request->peserta_non_tna as $row) {
                if (!empty($row['nama']) || !empty($row['subco'])) {
                    $pesertaNonTna[] = [
                        'nama'      => trim($row['nama'] ?? ''),
                        'npk'       => trim($row['npk'] ?? ''),
                        'subco'     => trim($row['subco'] ?? ''),
                        'pic_subco' => trim($row['pic_subco'] ?? ''),
                    ];
                }
            }
        }

        // Check if event already has training event
        $idEvent = $request->filled('id_event') ? $request->id_event : null;
        if ($idEvent) {
            $existing = CoeTrainingEvent::where('id_event', $idEvent)->first();
            if ($existing) {
                $existing->update([
                    'tipe_penyelenggara' => $request->tipe_penyelenggara,
                    'nama_penyelenggara' => trim($request->nama_penyelenggara),
                    'trainer'            => trim($request->trainer),
                    'manager_class'      => $request->filled('manager_class') ? trim($request->manager_class) : null,
                    'tipe_evaluasi'      => $request->filled('tipe_evaluasi') ? trim($request->tipe_evaluasi) : null,
                    'tipe_soal'          => $request->filled('tipe_soal') ? trim($request->tipe_soal) : null,
                    'ruangan'            => $request->filled('ruangan') ? trim($request->ruangan) : null,
                    'peserta_tna'        => $pesertaTna,
                    'peserta_non_tna'    => $pesertaNonTna,
                    'catatan'            => $request->catatan,
                ]);

                // Update jumlah peserta di coe_events jika peserta diisi
                if (count($pesertaTna) > 0 || count($pesertaNonTna) > 0) {
                    $event = CoeEvent::find($idEvent);
                    if ($event) {
                        $event->jumlah_peserta_tna = count($pesertaTna);
                        $event->jumlah_peserta_non_tna = count($pesertaNonTna);
                        $event->total_biaya = ($event->jumlah_peserta_tna + $event->jumlah_peserta_non_tna) * $event->biaya_investasi_perorang;
                        $event->save();
                    }
                }

                return redirect()->route('coe.training-event.index')
                    ->with('success', 'Data Training Event berhasil diperbarui.');
            }
        }

        $trainingEvent = CoeTrainingEvent::create([
            'id_event'           => $idEvent,
            'tipe_penyelenggara' => $request->tipe_penyelenggara,
            'nama_penyelenggara' => trim($request->nama_penyelenggara),
            'trainer'            => trim($request->trainer),
            'manager_class'      => $request->filled('manager_class') ? trim($request->manager_class) : null,
            'tipe_evaluasi'      => $request->filled('tipe_evaluasi') ? trim($request->tipe_evaluasi) : null,
            'tipe_soal'          => $request->filled('tipe_soal') ? trim($request->tipe_soal) : null,
            'ruangan'            => $request->filled('ruangan') ? trim($request->ruangan) : null,
            'peserta_tna'        => $pesertaTna,
            'peserta_non_tna'    => $pesertaNonTna,
            'catatan'            => $request->catatan,
        ]);

        // Sync jumlah peserta ke coe_events jika ada
        if ($idEvent && (count($pesertaTna) > 0 || count($pesertaNonTna) > 0)) {
            $event = CoeEvent::find($idEvent);
            if ($event) {
                $event->jumlah_peserta_tna = count($pesertaTna);
                $event->jumlah_peserta_non_tna = count($pesertaNonTna);
                $event->total_biaya = ($event->jumlah_peserta_tna + $event->jumlah_peserta_non_tna) * $event->biaya_investasi_perorang;
                $event->save();
            }
        }

        return redirect()->route('coe.training-event.index')
            ->with('success', 'Data Training Event berhasil disimpan.');
    }

    /**
     * Show the form for editing a Training Event
     */
    public function edit($id)
    {
        $trainingEvent = CoeTrainingEvent::with('coeEvent')->findOrFail($id);
        $allEvents = CoeEvent::orderBy('tanggal_per_batch', 'desc')->get();
        $providers = ProviderModel::orderBy('provider_name')->get();
        $instructors = InstructorModel::orderBy('instructor_name')->get();
        $venues = VenueModel::orderBy('venue_name')->get();
        $allStaff = StaffModel::with(['divisi', 'department', 'levelJabatan', 'immediateManager'])
            ->orderBy('nama_staff', 'asc')
            ->get();

        return view('dlc.coe.training-event.edit', compact(
            'trainingEvent',
            'allEvents',
            'providers',
            'instructors',
            'venues',
            'allStaff'
        ));
    }

    /**
     * Update the specified Training Event
     */
    public function update(Request $request, $id)
    {
        $trainingEvent = CoeTrainingEvent::findOrFail($id);

        $request->validate([
            'tipe_penyelenggara' => 'required|in:Internal,External',
            'nama_penyelenggara' => 'required|string|max:255',
            'trainer'            => 'required|string|max:255',
            'manager_class'      => 'nullable|string|max:255',
            'tipe_evaluasi'      => 'nullable|string|max:255',
            'tipe_soal'          => 'nullable|string|max:255',
            'ruangan'            => 'nullable|string|max:255',
            'id_event'           => 'nullable|exists:coe_events,id_event',
        ], [
            'tipe_penyelenggara.required' => 'Tipe penyelenggara wajib dipilih.',
            'nama_penyelenggara.required' => 'Nama penyelenggara wajib diisi.',
            'trainer.required'            => 'Nama trainer wajib diisi.',
        ]);

        // Filter and sanitize Peserta TNA
        $pesertaTna = [];
        if ($request->has('peserta_tna') && is_array($request->peserta_tna)) {
            foreach ($request->peserta_tna as $row) {
                if (!empty($row['nama']) || !empty($row['npk'])) {
                    $pesertaTna[] = [
                        'npk'      => trim($row['npk'] ?? ''),
                        'nama'     => trim($row['nama'] ?? ''),
                        'jabatan'  => trim($row['jabatan'] ?? ''),
                        'divisi'   => trim($row['divisi'] ?? ''),
                        'atasan'   => trim($row['atasan'] ?? ''),
                    ];
                }
            }
        }

        // Filter and sanitize Peserta Non-TNA
        $pesertaNonTna = [];
        if ($request->has('peserta_non_tna') && is_array($request->peserta_non_tna)) {
            foreach ($request->peserta_non_tna as $row) {
                if (!empty($row['nama']) || !empty($row['subco'])) {
                    $pesertaNonTna[] = [
                        'nama'      => trim($row['nama'] ?? ''),
                        'npk'       => trim($row['npk'] ?? ''),
                        'subco'     => trim($row['subco'] ?? ''),
                        'pic_subco' => trim($row['pic_subco'] ?? ''),
                    ];
                }
            }
        }

        $idEvent = $request->filled('id_event') ? $request->id_event : null;

        $trainingEvent->update([
            'id_event'           => $idEvent,
            'tipe_penyelenggara' => $request->tipe_penyelenggara,
            'nama_penyelenggara' => trim($request->nama_penyelenggara),
            'trainer'            => trim($request->trainer),
            'manager_class'      => $request->filled('manager_class') ? trim($request->manager_class) : null,
            'tipe_evaluasi'      => $request->filled('tipe_evaluasi') ? trim($request->tipe_evaluasi) : null,
            'tipe_soal'          => $request->filled('tipe_soal') ? trim($request->tipe_soal) : null,
            'ruangan'            => $request->filled('ruangan') ? trim($request->ruangan) : null,
            'peserta_tna'        => $pesertaTna,
            'peserta_non_tna'    => $pesertaNonTna,
            'catatan'            => $request->catatan,
        ]);

        // Sync jumlah peserta ke coe_events jika ada
        if ($idEvent && (count($pesertaTna) > 0 || count($pesertaNonTna) > 0)) {
            $event = CoeEvent::find($idEvent);
            if ($event) {
                $event->jumlah_peserta_tna = count($pesertaTna);
                $event->jumlah_peserta_non_tna = count($pesertaNonTna);
                $event->total_biaya = ($event->jumlah_peserta_tna + $event->jumlah_peserta_non_tna) * $event->biaya_investasi_perorang;
                $event->save();
            }
        }

        return redirect()->route('coe.training-event.index')
            ->with('success', 'Data Training Event berhasil diperbarui.');
    }

    /**
     * Remove the specified Training Event
     */
    public function destroy($id)
    {
        $trainingEvent = CoeTrainingEvent::findOrFail($id);
        $trainingEvent->delete();

        return redirect()->route('coe.training-event.index')
            ->with('success', 'Data Training Event berhasil dihapus.');
    }
}
