<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffModel;
use App\Models\EmailConfigModel;
use App\Helpers\StaffSpreadsheetHelper;
use Illuminate\Support\Facades\Log;

class PenerimaEmailController extends Controller
{
    /**
     * Tampilkan tabel daftar Immediate Manager beserta kontak email
     */
    public function index(Request $request)
    {
        // Ambil ID semua staff yang menjadi Immediate Manager
        $managerIds = StaffModel::whereNotNull('id_immediate_manager')
            ->pluck('id_immediate_manager')
            ->unique()
            ->filter()
            ->toArray();

        // Query staff yang terdaftar sebagai Immediate Manager atau memiliki jabatan manajerial (bukan Staff biasa)
        $query = StaffModel::with(['department', 'divisi', 'levelJabatan'])
            ->where(function ($q) use ($managerIds) {
                $q->whereIn('id_staff', $managerIds)
                  ->orWhere('id_jabatan_staff', '!=', 4);
            });

        // Fitur pencarian berdasarkan NPK, Nama, Email, Department, atau Divisi
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('npk_staff', 'like', "%{$search}%")
                  ->orWhere('nama_staff', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('nama_department', 'like', "%{$search}%");
                  })
                  ->orWhereHas('divisi', function ($vq) use ($search) {
                      $vq->where('nama_divisi', 'like', "%{$search}%");
                  });
            });
        }

        $managers = $query->orderBy('nama_staff', 'asc')->get();

        // Hitung statistik keseluruhan Immediate Manager
        $allManagers = StaffModel::where(function ($q) use ($managerIds) {
                $q->whereIn('id_staff', $managerIds)
                  ->orWhere('id_jabatan_staff', '!=', 4);
            })->get();

        $totalManagers = $allManagers->count();
        $withEmailCount = $allManagers->whereNotNull('email')->where('email', '!=', '')->count();
        $missingEmailCount = $totalManagers - $withEmailCount;

        // Pastikan konfigurasi recipients selalu sinkron
        self::syncEmailConfigRecipients();

        return view('dlc.penerimaemail', compact(
            'managers',
            'totalManagers',
            'withEmailCount',
            'missingEmailCount'
        ));
    }

    /**
     * Update kontak (Email) untuk satu Immediate Manager
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'nullable|email|max:150',
        ], [
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Panjang email maksimal 150 karakter.',
        ]);

        $manager = StaffModel::findOrFail($id);
        $email = $request->filled('email') ? trim(strtolower($request->email)) : null;
        $manager->email = $email;
        $manager->save();

        // Sinkronkan ke email_configs table
        self::syncEmailConfigRecipients();

        return redirect()->route('penerima-email')->with('success', "Kontak email untuk {$manager->nama_staff} (NPK: {$manager->npk_staff}) berhasil diperbarui.");
    }

    /**
     * Export daftar kontak Immediate Manager ke format CSV (Excel Ready)
     */
    public function export()
    {
        $managerIds = StaffModel::whereNotNull('id_immediate_manager')
            ->pluck('id_immediate_manager')
            ->unique()
            ->filter()
            ->toArray();

        $managers = StaffModel::with(['department', 'divisi', 'levelJabatan'])
            ->where(function ($q) use ($managerIds) {
                $q->whereIn('id_staff', $managerIds)
                  ->orWhere('id_jabatan_staff', '!=', 4);
            })
            ->orderBy('nama_staff', 'asc')
            ->get();

        $filename = 'kontak_immediate_manager_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($managers) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM untuk Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");
            fputs($handle, "sep=,\r\n");

            // Baris Header
            fputcsv($handle, [
                'No',
                'NPK',
                'Nama',
                'Kontak (Email)',
                'Jabatan',
                'Divisi',
                'Department',
            ]);

            $no = 1;
            foreach ($managers as $m) {
                $bagianDept = $m->department ? $m->department->nama_department : '-';
                $bagianDivisi = $m->divisi ? $m->divisi->nama_divisi : '-';
                $jabatan = $m->levelJabatan ? $m->levelJabatan->nama_level_jabatan : '-';

                fputcsv($handle, [
                    $no++,
                    $m->npk_staff,
                    $m->nama_staff,
                    $m->email ?? '',
                    $jabatan,
                    $bagianDivisi,
                    $bagianDept,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download template format CSV untuk import kontak email
     */
    public function template()
    {
        $filename = 'template_import_kontak_email_im.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Ambil data Immediate Manager saat ini untuk disertakan sebagai baris panduan/isian
        $managerIds = StaffModel::whereNotNull('id_immediate_manager')
            ->pluck('id_immediate_manager')
            ->unique()
            ->filter()
            ->toArray();

        $managers = StaffModel::where(function ($q) use ($managerIds) {
                $q->whereIn('id_staff', $managerIds)
                  ->orWhere('id_jabatan_staff', '!=', 4);
            })
            ->orderBy('nama_staff', 'asc')
            ->get();

        $callback = function () use ($managers) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");
            fputs($handle, "sep=,\r\n");

            // Header Row
            fputcsv($handle, [
                'NPK',
                'Nama',
                'Kontak (Email)',
            ]);

            if ($managers->count() > 0) {
                foreach ($managers as $m) {
                    fputcsv($handle, [
                        $m->npk_staff,
                        $m->nama_staff,
                        $m->email ?? '',
                    ]);
                }
            } else {
                fputcsv($handle, ['11990935', 'Haniful Qayyim Apip', 'haniful.qayyim@dharmap.com']);
                fputcsv($handle, ['99122022', 'Lukman Hawari Pratama', 'lukman.hawari@dharmap.com']);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import kontak email dari file CSV / TXT / Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ], [
            'file.required' => 'Silakan pilih berkas file untuk diimpor.',
            'file.max' => 'Ukuran berkas tidak boleh melebihi 5MB.',
        ]);

        $file = $request->file('file');
        try {
            $rows = StaffSpreadsheetHelper::readRowsFromAny($file);
        } catch (\Exception $e) {
            return redirect()->route('penerima-email')->with('error', 'Gagal membaca berkas import: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            return redirect()->route('penerima-email')->with('error', 'Berkas import kosong atau tidak memiliki data yang valid.');
        }

        $headerRow = array_shift($rows);
        $headerRow = array_map(function ($h) {
            return strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $h)));
        }, $headerRow);

        // Cari index kolom NPK dan Email
        $npkIndex = null;
        $emailIndex = null;

        foreach ($headerRow as $idx => $col) {
            if (in_array($col, ['npk', 'npkstaff', 'nomornpk'])) {
                $npkIndex = $idx;
            }
            if (in_array($col, ['kontakemail', 'email', 'kontak', 'alamatemail', 'surel'])) {
                $emailIndex = $idx;
            }
        }

        // Jika tidak ditemukan lewat nama kolom spesifik, tebak index default
        if ($npkIndex === null) {
            $npkIndex = 0; // Kolom pertama biasanya NPK
        }
        if ($emailIndex === null) {
            // Kolom terakhir atau kolom dengan kata email
            $emailIndex = count($headerRow) >= 3 ? 2 : 1;
        }

        $updatedCount = 0;
        $skippedCount = 0;
        $notFoundNpks = [];

        foreach ($rows as $row) {
            if (empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                continue;
            }

            $rawNpk = trim($row[$npkIndex] ?? '');
            $rawEmail = trim($row[$emailIndex] ?? '');

            if ($rawNpk === '') {
                $skippedCount++;
                continue;
            }

            // Cari staff berdasarkan NPK
            $staff = StaffModel::where('npk_staff', $rawNpk)->first();

            if (!$staff) {
                $notFoundNpks[] = $rawNpk;
                $skippedCount++;
                continue;
            }

            // Validasi format email jika ada isinya
            $validEmail = null;
            if ($rawEmail !== '') {
                $cleanEmail = strtolower($rawEmail);
                if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                    $validEmail = $cleanEmail;
                } else {
                    // Coba bersihkan karakter yang tidak diinginkan
                    $sanitized = filter_var($cleanEmail, FILTER_SANITIZE_EMAIL);
                    if (filter_var($sanitized, FILTER_VALIDATE_EMAIL)) {
                        $validEmail = $sanitized;
                    }
                }
            }

            $staff->email = $validEmail;
            $staff->save();
            $updatedCount++;
        }

        // Sinkronkan ke konfigurasi global
        self::syncEmailConfigRecipients();

        $message = "Proses import selesai: {$updatedCount} kontak email berhasil diperbarui.";
        if (!empty($notFoundNpks)) {
            $uniqueNotFound = array_unique($notFoundNpks);
            $message .= " (" . count($uniqueNotFound) . " NPK tidak ditemukan di sistem: " . implode(', ', array_slice($uniqueNotFound, 0, 5)) . ")";
        }

        return redirect()->route('penerima-email')->with('success', $message);
    }

    /**
     * Fallback penyimpanan teks baris email manual (kompatibilitas backward)
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'nullable|string',
        ]);

        if ($request->filled('recipients')) {
            EmailConfigModel::setValue('recipients', $request->recipients);
        }

        return redirect()->route('penerima-email')->with('success', 'Konfigurasi daftar email penerima berhasil disimpan.');
    }

    /**
     * Helper privat untuk memastikan email_configs.recipients selalu sinkron
     */
    public static function syncEmailConfigRecipients()
    {
        $managerIds = StaffModel::whereNotNull('id_immediate_manager')
            ->pluck('id_immediate_manager')
            ->unique()
            ->filter()
            ->toArray();

        $emails = StaffModel::where(function ($q) use ($managerIds) {
                $q->whereIn('id_staff', $managerIds)
                  ->orWhere('id_jabatan_staff', '!=', 4);
            })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->map(fn($e) => trim(strtolower($e)))
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        EmailConfigModel::setValue('recipients', implode("\n", $emails));
    }
}
