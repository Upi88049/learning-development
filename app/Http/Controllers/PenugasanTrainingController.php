<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenugasanTrainingModel;
use App\Models\RequestOuthouseModel;
use App\Models\StaffModel;
use App\Helpers\TerbilangHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PenugasanTrainingController extends Controller
{
    /**
     * Display list of training assignment forms (DLC)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = PenugasanTrainingModel::with('requestOuthouse')->orderBy('id_penugasan', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_training', 'like', "%{$search}%")
                  ->orWhere('divisi', 'like', "%{$search}%")
                  ->orWhere('sub_co', 'like', "%{$search}%")
                  ->orWhere('tempat_tanggal_training', 'like', "%{$search}%")
                  ->orWhere('peserta_json', 'like', "%{$search}%");
            });
        }

        $penugasanList = $query->get();

        return view('dlc.penugasan.index', compact('penugasanList', 'search'));
    }

    /**
     * Show form to create new assignment form
     */
    public function create(Request $request)
    {
        $fromRequestId = $request->query('from_request');
        $requestOuthouse = null;

        $defaultData = [
            'no_form' => 'Form 013/WI-',
            'id_request_outhouse' => null,
            'nama_training' => '',
            'jenis_training' => 'Out House Training',
            'sub_co' => 'PT. Dharma Polimetal Tbk',
            'divisi' => '',
            'peserta' => [],
            'jumlah_peserta' => 1,
            'biaya_per_peserta' => 0,
            'total_biaya' => 0,
            'terbilang' => '',
            'alasan_pelatihan' => '',
            'nama_atasan' => '',
            'divisi_atasan' => '',
            'jabatan_atasan' => '',
            'tempat_tanggal_training' => '',
            'tempat_tanggal_persetujuan' => 'Cikarang, ' . Carbon::now()->translatedFormat('d F Y'),
            'penyetujui_nama' => '',
            'penyetujui_jabatan' => 'Director',
            'konfirmasi_nama' => 'Herwin Gultom',
            'konfirmasi_jabatan' => 'HRGA Deputy Div. Head',
        ];

        if ($fromRequestId) {
            $requestOuthouse = RequestOuthouseModel::with(['staff.divisi', 'staff.department', 'staff.levelJabatan', 'immediateManager'])->find($fromRequestId);
            if ($requestOuthouse) {
                $staff = $requestOuthouse->staff;
                $manager = $requestOuthouse->immediateManager ?: ($staff ? $staff->immediateManager : null);

                $defaultData['id_request_outhouse'] = $requestOuthouse->id_request_outhouse;
                $defaultData['nama_training'] = $requestOuthouse->judul_training;
                $defaultData['jenis_training'] = 'Out House Training';
                $defaultData['divisi'] = $staff && $staff->divisi ? $staff->divisi->nama_divisi : '';
                $defaultData['alasan_pelatihan'] = $requestOuthouse->reason;

                if ($staff) {
                    $defaultData['peserta'][] = [
                        'npk' => $staff->npk_staff,
                        'nama' => $staff->nama_staff,
                        'bagian' => $staff->department ? $staff->department->nama_department : '-',
                        'jabatan' => $staff->levelJabatan ? $staff->levelJabatan->kode_level_jabatan : 'SF',
                        'atasan' => $manager ? $manager->nama_staff : '-',
                        'paraf' => '',
                    ];
                }

                if ($manager) {
                    $defaultData['nama_atasan'] = $manager->nama_staff;
                    $defaultData['divisi_atasan'] = $manager->divisi ? $manager->divisi->nama_divisi : $defaultData['divisi'];
                    $defaultData['jabatan_atasan'] = $manager->levelJabatan ? $manager->levelJabatan->kode_level_jabatan : 'Manager';
                }
            }
        }

        if (empty($defaultData['peserta'])) {
            $defaultData['peserta'][] = [
                'npk' => '',
                'nama' => '',
                'bagian' => '',
                'jabatan' => '',
                'atasan' => '',
                'paraf' => '',
            ];
        }

        $allStaff = StaffModel::with(['divisi', 'department', 'levelJabatan', 'immediateManager'])
            ->orderBy('nama_staff', 'asc')
            ->get();

        return view('dlc.penugasan.create', compact('defaultData', 'requestOuthouse', 'allStaff'));
    }

    /**
     * Store new assignment form in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_training' => 'required|string|max:255',
            'jenis_training' => 'required|string|max:100',
            'sub_co' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'peserta' => 'required|array|min:1',
            'peserta.*.nama' => 'required|string|max:255',
            'biaya_per_peserta' => 'required|numeric|min:0',
        ], [
            'nama_training.required' => 'Nama training wajib diisi.',
            'jenis_training.required' => 'Jenis training wajib diisi.',
            'sub_co.required' => 'SubCo wajib diisi.',
            'divisi.required' => 'Divisi wajib diisi.',
            'peserta.required' => 'Minimal 1 peserta wajib diisi.',
            'peserta.*.nama.required' => 'Nama lengkap peserta wajib diisi.',
            'biaya_per_peserta.required' => 'Biaya investasi per peserta wajib diisi.',
        ]);

        $pesertaList = [];
        foreach ($request->peserta as $p) {
            if (!empty($p['nama']) || !empty($p['npk'])) {
                $pesertaList[] = [
                    'npk' => $p['npk'] ?? '',
                    'nama' => $p['nama'] ?? '',
                    'bagian' => $p['bagian'] ?? '',
                    'jabatan' => $p['jabatan'] ?? '',
                    'atasan' => $p['atasan'] ?? '',
                    'paraf' => $p['paraf'] ?? '',
                ];
            }
        }

        if (empty($pesertaList)) {
            return redirect()->back()->withInput()->with('error', 'Mohon isi minimal 1 data peserta training.');
        }

        $jumlahPeserta = count($pesertaList);
        $biayaPerPeserta = (float)$request->biaya_per_peserta;
        $totalBiaya = $jumlahPeserta * $biayaPerPeserta;

        $terbilang = !empty($request->terbilang) ? trim($request->terbilang) : TerbilangHelper::convert($totalBiaya);

        $penugasan = PenugasanTrainingModel::create([
            'no_form' => $request->no_form ?: 'Form 013/WI-',
            'id_request_outhouse' => $request->id_request_outhouse ?: null,
            'nama_training' => $request->nama_training,
            'jenis_training' => $request->jenis_training,
            'sub_co' => $request->sub_co,
            'divisi' => $request->divisi,
            'peserta_json' => json_encode($pesertaList),
            'jumlah_peserta' => $jumlahPeserta,
            'biaya_per_peserta' => $biayaPerPeserta,
            'total_biaya' => $totalBiaya,
            'terbilang' => $terbilang,
            'alasan_pelatihan' => $request->alasan_pelatihan,
            'nama_atasan' => $request->nama_atasan,
            'divisi_atasan' => $request->divisi_atasan,
            'jabatan_atasan' => $request->jabatan_atasan,
            'tempat_tanggal_training' => $request->tempat_tanggal_training,
            'tempat_tanggal_persetujuan' => $request->tempat_tanggal_persetujuan,
            'penyetujui_nama' => $request->penyetujui_nama,
            'penyetujui_jabatan' => $request->penyetujui_jabatan ?: 'Director',
            'konfirmasi_nama' => $request->konfirmasi_nama ?: 'Herwin Gultom',
            'konfirmasi_jabatan' => $request->konfirmasi_jabatan ?: 'HRGA Deputy Div. Head',
        ]);

        if ($request->has('action_save_download')) {
            return redirect()->route('penugasan.downloadPdf', $penugasan->id_penugasan);
        }

        return redirect()->route('penugasan.index')->with('success', "Formulir Pendaftaran & Penugasan Training ({$penugasan->nama_training}) berhasil dibuat.");
    }

    /**
     * Show form to edit existing assignment form
     */
    public function edit($id)
    {
        $penugasan = PenugasanTrainingModel::findOrFail($id);
        $allStaff = StaffModel::with(['divisi', 'department', 'levelJabatan', 'immediateManager'])
            ->orderBy('nama_staff', 'asc')
            ->get();

        return view('dlc.penugasan.edit', compact('penugasan', 'allStaff'));
    }

    /**
     * Update existing assignment form
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_training' => 'required|string|max:255',
            'jenis_training' => 'required|string|max:100',
            'sub_co' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'peserta' => 'required|array|min:1',
            'peserta.*.nama' => 'required|string|max:255',
            'biaya_per_peserta' => 'required|numeric|min:0',
        ]);

        $penugasan = PenugasanTrainingModel::findOrFail($id);

        $pesertaList = [];
        foreach ($request->peserta as $p) {
            if (!empty($p['nama']) || !empty($p['npk'])) {
                $pesertaList[] = [
                    'npk' => $p['npk'] ?? '',
                    'nama' => $p['nama'] ?? '',
                    'bagian' => $p['bagian'] ?? '',
                    'jabatan' => $p['jabatan'] ?? '',
                    'atasan' => $p['atasan'] ?? '',
                    'paraf' => $p['paraf'] ?? '',
                ];
            }
        }

        if (empty($pesertaList)) {
            return redirect()->back()->withInput()->with('error', 'Mohon isi minimal 1 data peserta training.');
        }

        $jumlahPeserta = count($pesertaList);
        $biayaPerPeserta = (float)$request->biaya_per_peserta;
        $totalBiaya = $jumlahPeserta * $biayaPerPeserta;

        $terbilang = !empty($request->terbilang) ? trim($request->terbilang) : TerbilangHelper::convert($totalBiaya);

        $penugasan->update([
            'no_form' => $request->no_form ?: 'Form 013/WI-',
            'nama_training' => $request->nama_training,
            'jenis_training' => $request->jenis_training,
            'sub_co' => $request->sub_co,
            'divisi' => $request->divisi,
            'peserta_json' => json_encode($pesertaList),
            'jumlah_peserta' => $jumlahPeserta,
            'biaya_per_peserta' => $biayaPerPeserta,
            'total_biaya' => $totalBiaya,
            'terbilang' => $terbilang,
            'alasan_pelatihan' => $request->alasan_pelatihan,
            'nama_atasan' => $request->nama_atasan,
            'divisi_atasan' => $request->divisi_atasan,
            'jabatan_atasan' => $request->jabatan_atasan,
            'tempat_tanggal_training' => $request->tempat_tanggal_training,
            'tempat_tanggal_persetujuan' => $request->tempat_tanggal_persetujuan,
            'penyetujui_nama' => $request->penyetujui_nama,
            'penyetujui_jabatan' => $request->penyetujui_jabatan ?: 'Director',
            'konfirmasi_nama' => $request->konfirmasi_nama ?: 'Herwin Gultom',
            'konfirmasi_jabatan' => $request->konfirmasi_jabatan ?: 'HRGA Deputy Div. Head',
        ]);

        if ($request->has('action_save_download')) {
            return redirect()->route('penugasan.downloadPdf', $penugasan->id_penugasan);
        }

        return redirect()->route('penugasan.index')->with('success', "Formulir Pendaftaran & Penugasan Training ({$penugasan->nama_training}) berhasil diperbarui.");
    }

    /**
     * Delete assignment form
     */
    public function destroy($id)
    {
        $penugasan = PenugasanTrainingModel::findOrFail($id);
        $nama = $penugasan->nama_training;
        $penugasan->delete();

        return redirect()->route('penugasan.index')->with('success', "Formulir Penugasan Training ({$nama}) berhasil dihapus.");
    }

    /**
     * Download Formulir Pendaftaran & Penugasan Training as PDF
     */
    public function downloadPdf($id)
    {
        $penugasan = PenugasanTrainingModel::findOrFail($id);

        $pdf = Pdf::loadView('dlc.penugasan.pdf', compact('penugasan'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $filename = 'Form_Pendaftaran_Penugasan_Training_' . preg_replace('/[^A-Za-z0-9]/', '_', $penugasan->nama_training) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Preview Formulir Pendaftaran & Penugasan Training in browser
     */
    public function previewPdf($id)
    {
        $penugasan = PenugasanTrainingModel::findOrFail($id);

        $pdf = Pdf::loadView('dlc.penugasan.pdf', compact('penugasan'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        return $pdf->stream('Form_Pendaftaran_Penugasan_Training.pdf');
    }
}
