<?php

namespace App\Helpers;

use App\Models\StaffModel;
use App\Models\DepartmentModel;
use App\Models\DivisiModel;
use App\Models\LevelJabatanModel;
use App\Models\StaffTrainingModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class StaffSpreadsheetHelper
{
    /**
     * Generate & download genuine CSV file configured for Microsoft Excel without security warning
     */
    public static function exportStaff($staffList, $departmentName = null, $divisiName = null)
    {
        $suffix = '';
        if ($divisiName && $divisiName !== 'all') {
            $suffix .= '_divisi_' . preg_replace('/[^a-zA-Z0-9]/', '_', $divisiName);
        }
        if ($departmentName && $departmentName !== 'all') {
            $suffix .= '_dept_' . preg_replace('/[^a-zA-Z0-9]/', '_', $departmentName);
        }
        $filename = 'data_staff' . $suffix . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($staffList) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");
            // Instruct Excel explicitly what delimiter to use
            fputs($handle, "sep=,\r\n");

            // Header row
            fputcsv($handle, [
                'NPK',
                'Nama Staff',
                'Tanggal Lahir (YYYY-MM-DD)',
                'Divisi',
                'Department',
                'Level Jabatan',
                'NPK Immediate Manager',
            ]);

            foreach ($staffList as $s) {
                fputcsv($handle, [
                    $s->npk_staff,
                    $s->nama_staff,
                    $s->tanggal_lahir ? $s->tanggal_lahir->format('Y-m-d') : '',
                    $s->divisi ? $s->divisi->nama_divisi : '',
                    $s->department ? $s->department->nama_department : '',
                    $s->levelJabatan ? $s->levelJabatan->kode_level_jabatan : '',
                    $s->immediateManager ? $s->immediateManager->npk_staff : '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download template format Excel / CSV for Staff Member
     */
    public static function downloadTemplate()
    {
        $filename = 'template_import_staff.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");
            // Instruct Excel explicitly what delimiter to use
            fputs($handle, "sep=,\r\n");

            // Header row
            fputcsv($handle, [
                'NPK',
                'Nama Staff',
                'Tanggal Lahir (YYYY-MM-DD)',
                'Divisi',
                'Department',
                'Level Jabatan',
                'NPK Immediate Manager',
            ]);

            // Sample row 1: Normal (Divisi + Department ada)
            fputcsv($handle, [
                '11990999',
                'Contoh Budi Santoso',
                '1995-08-17',
                'Human Capital & General Affairs',
                'GA & IR',
                'SF',
                '11990935',
            ]);

            // Sample row 2: Divisi Saja (Department N/A)
            fputcsv($handle, [
                '11990888',
                'Contoh Hendra Wijaya',
                '1985-03-20',
                'Information Technology',
                'N/A',
                'DH',
                '',
            ]);

            // Sample row 3: Department Saja (Divisi N/A)
            fputcsv($handle, [
                '11990777',
                'Contoh Siti Rahma',
                '1998-12-25',
                'N/A',
                'Learning & Development',
                'SF',
                '11990888',
            ]);

            // Sample row 4: Keduanya N/A
            fputcsv($handle, [
                '11990666',
                'Contoh Rian Prakoso',
                '2001-07-10',
                'N/A',
                'N/A',
                'SF',
                '',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download template format Excel / CSV specifically for Training History
     * Columns: NPK, Nama, Divisi, Department, Sudah Terlaksana, Mandatory Training, Tidak Hadir, In House Training
     */
    public static function downloadTrainingHistoryTemplate()
    {
        $filename = 'template_history_training_staff.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Fetch master trainings for quick reference in notes
        $trainings = UserModel::orderBy('id_training', 'asc')->get();

        $callback = function () use ($trainings) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");
            // Instruct Excel explicitly what delimiter to use
            fputs($handle, "sep=,\r\n");

            // Header row (8 Columns as requested)
            fputcsv($handle, [
                'NPK',
                'Nama',
                'Divisi',
                'Department',
                'Sudah Terlaksana',
                'Mandatory Training',
                'Tidak Hadir',
                'In House Training',
            ]);

            // Sample row 1
            fputcsv($handle, [
                '11240206',
                'Edgar Ronaldo Silalahi',
                'Human Capital',
                'Personalia',
                '1, 2, 5',
                '3',
                '4',
                '6',
            ]);

            // Sample row 2
            fputcsv($handle, [
                '11174500',
                'Budhiana Irawati',
                'Human Capital',
                'Personalia',
                '1, 3',
                '2',
                '',
                '',
            ]);

            // Sample row 3
            fputcsv($handle, [
                '11164130',
                'Indra Ramayana Lumban G',
                'Human Capital',
                'Personalia',
                '2',
                '',
                '',
                '1, 4',
            ]);

            // Blank line separator for training guide
            fputcsv($handle, []);
            fputcsv($handle, ['--- PANDUAN PENGISIAN ID TRAINING ---']);
            fputcsv($handle, ['ID Training', 'Nama Training', 'Kategori / Jenis', 'Mandatory Info']);

            foreach ($trainings as $t) {
                fputcsv($handle, [
                    $t->id_training,
                    $t->nama_training,
                    $t->jenis_training ?: '-',
                    $t->mandatory_training ?: '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process import from UploadedFile or file path (Staff Master)
     */
    public static function importFromFile($fileOrPath)
    {
        if ($fileOrPath instanceof UploadedFile) {
            if (!$fileOrPath->isValid()) {
                throw new \Exception('Berkas yang diunggah tidak valid atau terjadi kesalahan upload.');
            }
            $rawContent = file_get_contents($fileOrPath->getPathname() ?: $fileOrPath->getRealPath());
            if (empty($rawContent)) {
                $rawContent = $fileOrPath->get();
            }
        } elseif (is_string($fileOrPath) && file_exists($fileOrPath)) {
            $rawContent = file_get_contents($fileOrPath);
        } else {
            throw new \Exception('Berkas import tidak ditemukan.');
        }

        return self::importFromString($rawContent);
    }

    /**
     * Process import specifically for Staff Training History
     */
    public static function importStaffTrainingHistory($fileOrPath)
    {
        if ($fileOrPath instanceof UploadedFile) {
            if (!$fileOrPath->isValid()) {
                throw new \Exception('Berkas yang diunggah tidak valid atau terjadi kesalahan upload.');
            }
            $rawContent = file_get_contents($fileOrPath->getPathname() ?: $fileOrPath->getRealPath());
            if (empty($rawContent)) {
                $rawContent = $fileOrPath->get();
            }
        } elseif (is_string($fileOrPath) && file_exists($fileOrPath)) {
            $rawContent = file_get_contents($fileOrPath);
        } else {
            throw new \Exception('Berkas import tidak ditemukan.');
        }

        return self::importTrainingHistoryFromString($rawContent);
    }

    /**
     * Parse raw string content and upsert staff training history
     */
    public static function importTrainingHistoryFromString(string $rawContent)
    {
        if (empty(trim($rawContent))) {
            throw new \Exception('Berkas import kosong.');
        }

        $rows = [];

        // Check if file is HTML / XML table (like .xls table)
        if (stripos($rawContent, '<table') !== false && stripos($rawContent, '<tr') !== false) {
            $rows = self::parseHtmlTable($rawContent);
        } else {
            // Standard CSV / TSV format
            $rawLines = preg_split('/\r\n|\r|\n/', $rawContent);
            $cleanedLines = [];

            foreach ($rawLines as $lineIndex => $line) {
                if ($lineIndex === 0) {
                    $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);
                }
                if (stripos(trim($line), 'sep=') === 0) {
                    continue;
                }
                // Stop if reached the guide/notes section
                if (str_contains($line, '--- PANDUAN')) {
                    break;
                }
                if (trim($line) !== '') {
                    $cleanedLines[] = $line;
                }
            }

            if (!empty($cleanedLines)) {
                $firstLine = $cleanedLines[0];
                $delimiters = [';', ',', "\t", '|'];
                $detectedDelimiter = ';';
                $maxCount = 0;

                foreach ($delimiters as $delim) {
                    $count = count(str_getcsv($firstLine, $delim));
                    if ($count > $maxCount) {
                        $maxCount = $count;
                        $detectedDelimiter = $delim;
                    }
                }

                foreach ($cleanedLines as $line) {
                    $row = str_getcsv($line, $detectedDelimiter);
                    if (!empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                        $rows[] = array_map('trim', $row);
                    }
                }
            }
        }

        if (count($rows) < 2) {
            throw new \Exception('Berkas harus memiliki header dan minimal 1 baris data staff.');
        }

        // Header mapping
        $rawHeaders = array_shift($rows);
        $headerMap = self::mapTrainingHistoryHeaders($rawHeaders);

        // Preload all valid training IDs
        $validTrainingIds = UserModel::pluck('id_training')->toArray();
        $validTrainingSet = array_flip($validTrainingIds);

        $staffUpdatedCount = 0;
        $totalRecordsSaved = 0;
        $errors = [];

        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2;

            $npk = isset($headerMap['npk']) && isset($row[$headerMap['npk']]) ? trim($row[$headerMap['npk']]) : '';
            $nama = isset($headerMap['nama']) && isset($row[$headerMap['nama']]) ? trim($row[$headerMap['nama']]) : '';

            // Clean numeric NPK
            $npkClean = preg_replace('/[^0-9]/', '', $npk);
            if (empty($npkClean)) {
                continue; // Skip guide or empty rows
            }

            $staff = StaffModel::where('npk_staff', (int)$npkClean)->first();
            if (!$staff) {
                $errors[] = "Baris #{$rowNumber}: Staff dengan NPK '{$npkClean}' " . ($nama ? "({$nama}) " : '') . "tidak ditemukan di sistem.";
                continue;
            }

            // Smart Name Verification: Check if name in Excel matches the staff in DB
            if (!empty($nama) && !self::isNameMatching($nama, $staff->nama_staff)) {
                $errors[] = "Baris #{$rowNumber}: Ketidakcocokan data! NPK '{$npkClean}' terdaftar atas nama '{$staff->nama_staff}', tetapi di file Excel tertulis '{$nama}'. Baris ini dilewati demi mencegah salah penetapan riwayat training.";
                continue;
            }

            // Extract training IDs for each status column
            $sudahTerlaksanaRaw = isset($headerMap['sudah_terlaksana']) && isset($row[$headerMap['sudah_terlaksana']]) ? $row[$headerMap['sudah_terlaksana']] : '';
            $mandatoryRaw = isset($headerMap['mandatory']) && isset($row[$headerMap['mandatory']]) ? $row[$headerMap['mandatory']] : '';
            $tidakHadirRaw = isset($headerMap['tidak_hadir']) && isset($row[$headerMap['tidak_hadir']]) ? $row[$headerMap['tidak_hadir']] : '';
            $inHouseRaw = isset($headerMap['in_house']) && isset($row[$headerMap['in_house']]) ? $row[$headerMap['in_house']] : '';

            $statusLabels = [
                1 => 'Sudah Terlaksana',
                3 => 'Tidak Hadir',
                2 => 'Mandatory Training',
                4 => 'In House Training',
            ];

            // Priority rank: 1 (Sudah Terlaksana) > 3 (Tidak Hadir) > 2 (Mandatory) > 4 (In House)
            $statusPriorityRank = [
                1 => 1, // Highest priority
                3 => 2,
                2 => 3,
                4 => 4, // Lowest priority
            ];

            $extractedPerColumn = [
                1 => self::extractTrainingIds($sudahTerlaksanaRaw),
                2 => self::extractTrainingIds($mandatoryRaw),
                3 => self::extractTrainingIds($tidakHadirRaw),
                4 => self::extractTrainingIds($inHouseRaw),
            ];

            // Resolve conflicts per training ID for this staff
            $resolvedForStaff = []; // id_training => ['status_id' => X, 'found_in' => [...]]

            foreach ($extractedPerColumn as $statusId => $trainingIds) {
                foreach ($trainingIds as $tId) {
                    if (!isset($validTrainingSet[$tId])) {
                        $errors[] = "Baris #{$rowNumber} (NPK {$npkClean}): ID Training '{$tId}' tidak terdaftar di master training.";
                        continue;
                    }

                    if (!isset($resolvedForStaff[$tId])) {
                        $resolvedForStaff[$tId] = [
                            'status_id' => $statusId,
                            'found_in' => [$statusId],
                        ];
                    } else {
                        $resolvedForStaff[$tId]['found_in'][] = $statusId;
                        $currentRank = $statusPriorityRank[$resolvedForStaff[$tId]['status_id']];
                        $newRank = $statusPriorityRank[$statusId];
                        if ($newRank < $currentRank) {
                            $resolvedForStaff[$tId]['status_id'] = $statusId;
                        }
                    }
                }
            }

            // Save resolved records and report conflicts
            $staffRecordCount = 0;

            foreach ($resolvedForStaff as $tId => $data) {
                $chosenStatus = $data['status_id'];
                $foundIn = array_unique($data['found_in']);

                if (count($foundIn) > 1) {
                    $columnNames = array_map(fn($s) => "'" . $statusLabels[$s] . "'", $foundIn);
                    $errors[] = "Baris #{$rowNumber} (NPK {$npkClean}): ID Training {$tId} diisi ganda pada kolom " . implode(' & ', $columnNames) . ". Sistem memprioritaskan status '{$statusLabels[$chosenStatus]}'.";
                }

                StaffTrainingModel::updateOrCreate(
                    [
                        'id_staff' => $staff->id_staff,
                        'id_training' => $tId,
                    ],
                    [
                        'id_status' => $chosenStatus,
                    ]
                );

                $staffRecordCount++;
                $totalRecordsSaved++;
            }

            if ($staffRecordCount > 0) {
                $staffUpdatedCount++;
            }
        }

        return [
            'staff_count' => $staffUpdatedCount,
            'training_records' => $totalRecordsSaved,
            'errors' => $errors,
        ];
    }

    /**
     * Helper to extract numeric IDs from string (comma, semicolon, space separated)
     */
    public static function extractTrainingIds($raw): array
    {
        if (empty($raw)) {
            return [];
        }
        preg_match_all('/\d+/', (string)$raw, $matches);
        if (empty($matches[0])) {
            return [];
        }
        return array_unique(array_map('intval', $matches[0]));
    }

    /**
     * Verify if the name in Excel reasonably matches the staff name in database
     */
    public static function isNameMatching(string $excelName, string $dbName): bool
    {
        $cleanExcel = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $excelName)));
        $cleanDb = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $dbName)));

        if (empty($cleanExcel)) {
            return true;
        }

        // Exact match
        if ($cleanExcel === $cleanDb) {
            return true;
        }

        // Substring match (e.g. "Edgar Ronaldo" in "Edgar Ronaldo Silalahi")
        if (str_contains($cleanDb, $cleanExcel) || str_contains($cleanExcel, $cleanDb)) {
            return true;
        }

        // Word overlap match (e.g. "Edgar Silalahi" vs "Edgar Ronaldo Silalahi")
        $excelWords = array_values(array_filter(explode(' ', $cleanExcel), fn($w) => strlen($w) > 1));
        $dbWords = array_values(array_filter(explode(' ', $cleanDb), fn($w) => strlen($w) > 1));
        $commonWords = array_intersect($excelWords, $dbWords);
        if (!empty($commonWords)) {
            return true;
        }

        // Text similarity percentage
        similar_text($cleanExcel, $cleanDb, $percent);
        if ($percent >= 60.0) {
            return true;
        }

        return false;
    }

    /**
     * Map headers specifically for Training History sheet
     */
    private static function mapTrainingHistoryHeaders(array $headers)
    {
        $map = [];
        foreach ($headers as $index => $header) {
            $h = strtolower(preg_replace('/[^a-z0-9]/', '', $header));
            if (str_contains($h, 'npk')) {
                $map['npk'] = $index;
            } elseif (str_contains($h, 'nama') || str_contains($h, 'name')) {
                $map['nama'] = $index;
            } elseif (str_contains($h, 'divisi') || str_contains($h, 'division')) {
                $map['divisi'] = $index;
            } elseif (str_contains($h, 'depart') || str_contains($h, 'dept')) {
                $map['dept'] = $index;
            } elseif (str_contains($h, 'sudah') || str_contains($h, 'terlaksana') || str_contains($h, 'selesai')) {
                $map['sudah_terlaksana'] = $index;
            } elseif (str_contains($h, 'mandatory') || str_contains($h, 'rekomendasi')) {
                $map['mandatory'] = $index;
            } elseif (str_contains($h, 'tidak') || str_contains($h, 'hadir') || str_contains($h, 'absen')) {
                $map['tidak_hadir'] = $index;
            } elseif (str_contains($h, 'inhouse') || str_contains($h, 'house')) {
                $map['in_house'] = $index;
            }
        }

        if (!isset($map['npk'])) $map['npk'] = 0;
        if (!isset($map['nama'])) $map['nama'] = 1;
        if (!isset($map['divisi'])) $map['divisi'] = 2;
        if (!isset($map['dept'])) $map['dept'] = 3;
        if (!isset($map['sudah_terlaksana'])) $map['sudah_terlaksana'] = 4;
        if (!isset($map['mandatory'])) $map['mandatory'] = 5;
        if (!isset($map['tidak_hadir'])) $map['tidak_hadir'] = 6;
        if (!isset($map['in_house'])) $map['in_house'] = 7;

        return $map;
    }

    /**
     * Parse raw string content and upsert staff data with flexible Divisi / Department handling
     */
    public static function importFromString(string $rawContent)
    {
        if (empty(trim($rawContent))) {
            throw new \Exception('Berkas import kosong.');
        }

        $rows = [];

        // Check if file is HTML / XML table (like .xls table)
        if (stripos($rawContent, '<table') !== false && stripos($rawContent, '<tr') !== false) {
            $rows = self::parseHtmlTable($rawContent);
        } else {
            // Standard CSV / TSV format
            $rawLines = preg_split('/\r\n|\r|\n/', $rawContent);
            $cleanedLines = [];

            foreach ($rawLines as $lineIndex => $line) {
                if ($lineIndex === 0) {
                    $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);
                }
                if (stripos(trim($line), 'sep=') === 0) {
                    continue;
                }
                if (trim($line) !== '') {
                    $cleanedLines[] = $line;
                }
            }

            if (!empty($cleanedLines)) {
                $firstLine = $cleanedLines[0];
                $delimiters = [';', ',', "\t", '|'];
                $detectedDelimiter = ';';
                $maxCount = 0;

                foreach ($delimiters as $delim) {
                    $count = count(str_getcsv($firstLine, $delim));
                    if ($count > $maxCount) {
                        $maxCount = $count;
                        $detectedDelimiter = $delim;
                    }
                }

                foreach ($cleanedLines as $line) {
                    $row = str_getcsv($line, $detectedDelimiter);
                    if (!empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                        $rows[] = array_map('trim', $row);
                    }
                }
            }
        }

        if (count($rows) < 2) {
            throw new \Exception('Berkas harus memiliki header dan minimal 1 baris data.');
        }

        // Header mapping
        $rawHeaders = array_shift($rows);
        $headerMap = self::mapHeaders($rawHeaders);

        // Preload reference data
        $divisiList = DivisiModel::all();
        $divisiByName = [];
        $divisiById = [];
        foreach ($divisiList as $div) {
            $divisiByName[strtolower(trim($div->nama_divisi))] = $div->id_divisi;
            $divisiById[$div->id_divisi] = $div->nama_divisi;
        }

        $departments = DepartmentModel::all();
        $deptByName = [];
        foreach ($departments as $d) {
            $deptByName[strtolower(trim($d->nama_department))] = $d;
        }

        $levels = LevelJabatanModel::all();
        $levelByCode = [];
        foreach ($levels as $l) {
            $levelByCode[strtolower(trim($l->kode_level_jabatan))] = $l->id_level_jabatan;
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $errors = [];

        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2;

            $npk = isset($headerMap['npk']) && isset($row[$headerMap['npk']]) ? trim($row[$headerMap['npk']]) : '';
            $nama = isset($headerMap['nama']) && isset($row[$headerMap['nama']]) ? trim($row[$headerMap['nama']]) : '';
            $tglLahirRaw = isset($headerMap['tgl_lahir']) && isset($row[$headerMap['tgl_lahir']]) ? trim($row[$headerMap['tgl_lahir']]) : '';
            $divisiRaw = isset($headerMap['divisi']) && isset($row[$headerMap['divisi']]) ? trim($row[$headerMap['divisi']]) : '';
            $deptRaw = isset($headerMap['dept']) && isset($row[$headerMap['dept']]) ? trim($row[$headerMap['dept']]) : '';
            $levelRaw = isset($headerMap['level']) && isset($row[$headerMap['level']]) ? trim($row[$headerMap['level']]) : '';
            $managerNpkRaw = isset($headerMap['manager']) && isset($row[$headerMap['manager']]) ? trim($row[$headerMap['manager']]) : '';

            // Clean numeric npk
            $npk = preg_replace('/[^0-9]/', '', $npk);
            if (empty($npk)) {
                $errors[] = "Baris #{$rowNumber}: NPK kosong atau tidak valid.";
                continue;
            }

            if (empty($nama)) {
                $errors[] = "Baris #{$rowNumber}: Nama Staff kosong.";
                continue;
            }

            // Resolve tanggal_lahir (handles DD/MM/YYYY, DD-MM-YYYY, YYYY-MM-DD, etc.)
            $tanggalLahir = null;
            if (!empty($tglLahirRaw)) {
                $cleanDate = trim($tglLahirRaw);
                if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $cleanDate, $m)) {
                    $tanggalLahir = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
                } elseif (preg_match('/^(\d{4})[\/\-\.](\d{1,2})[\/\-\.](\d{1,2})$/', $cleanDate, $m)) {
                    $tanggalLahir = sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
                } else {
                    try {
                        $tanggalLahir = Carbon::parse($cleanDate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $tanggalLahir = null;
                    }
                }
            }

            // Helper to check if string is N/A or empty
            $isNa = function ($val) {
                $v = strtolower(trim($val));
                return empty($v) || in_array($v, ['n/a', 'na', '-', 'none', 'null', 'tanpa divisi', 'tanpa department']);
            };

            // Resolve Divisi
            $idDivisi = null;
            $specifiedDivisiName = null;
            if (!$isNa($divisiRaw)) {
                $lookupDiv = strtolower(trim($divisiRaw));
                if (isset($divisiByName[$lookupDiv])) {
                    $idDivisi = $divisiByName[$lookupDiv];
                } else {
                    // Auto-create Divisi if not exists
                    $newDiv = DivisiModel::create(['nama_divisi' => trim($divisiRaw)]);
                    $idDivisi = $newDiv->id_divisi;
                    $divisiByName[$lookupDiv] = $idDivisi;
                    $divisiById[$idDivisi] = $newDiv->nama_divisi;
                }
                $specifiedDivisiName = trim($divisiRaw);
            }

            // Resolve Department
            $idDepartment = null;
            if (!$isNa($deptRaw)) {
                $lookupDept = strtolower(trim($deptRaw));
                if (isset($deptByName[$lookupDept])) {
                    $deptObj = $deptByName[$lookupDept];
                    $idDepartment = $deptObj->id_department;

                    // Relational consistency check
                    if ($deptObj->id_divisi) {
                        $actualDivisiId = $deptObj->id_divisi;
                        $actualDivisiName = $divisiById[$actualDivisiId] ?? 'Divisi Terkait';

                        // If user did not provide a Divisi, auto-assign from department
                        if (!$idDivisi) {
                            $idDivisi = $actualDivisiId;
                        }
                        // If user provided a different Divisi that doesn't match the department's master Divisi
                        elseif ($idDivisi !== $actualDivisiId) {
                            $errors[] = "Baris #{$rowNumber} (NPK {$npk}): Department '{$deptObj->nama_department}' menginduk ke Divisi '{$actualDivisiName}' (bukan '{$specifiedDivisiName}'). Sistem otomatis menyinkronkan Divisi staff ke '{$actualDivisiName}'.";
                            $idDivisi = $actualDivisiId; // Auto-sync to the master division
                        }
                    }
                } else {
                    // Auto-create Department if not exists
                    $newDept = DepartmentModel::create([
                        'nama_department' => trim($deptRaw),
                        'id_divisi' => $idDivisi,
                    ]);
                    $idDepartment = $newDept->id_department;
                    $deptByName[$lookupDept] = $newDept;
                }
            }

            // Resolve level jabatan
            $idJabatanStaff = null;
            if (!empty($levelRaw)) {
                $cleanLvl = strtolower(trim($levelRaw));
                if (isset($levelByCode[$cleanLvl])) {
                    $idJabatanStaff = $levelByCode[$cleanLvl];
                } else {
                    $newLevel = LevelJabatanModel::create([
                        'kode_level_jabatan' => strtoupper(trim($levelRaw)),
                        'keterangan' => strtoupper(trim($levelRaw)),
                    ]);
                    $idJabatanStaff = $newLevel->id_level_jabatan;
                    $levelByCode[$cleanLvl] = $idJabatanStaff;
                }
            }
            if (!$idJabatanStaff) {
                $idJabatanStaff = $levelByCode['sf'] ?? ($levels->first()?->id_level_jabatan ?? 1);
            }

            // Resolve immediate manager
            $idImmediateManager = null;
            if (!empty($managerNpkRaw)) {
                $cleanMgrNpk = preg_replace('/[^0-9]/', '', $managerNpkRaw);
                if (!empty($cleanMgrNpk)) {
                    $mgr = StaffModel::where('npk_staff', $cleanMgrNpk)->first();
                    if ($mgr) {
                        $idImmediateManager = $mgr->id_staff;
                    }
                }
            }

            // Upsert by NPK
            $existingStaff = StaffModel::where('npk_staff', $npk)->first();

            $dataToSave = [
                'npk_staff' => (int)$npk,
                'nama_staff' => $nama,
                'tanggal_lahir' => $tanggalLahir,
                'id_divisi' => $idDivisi,
                'id_department' => $idDepartment,
                'id_jabatan_staff' => $idJabatanStaff,
                'id_immediate_manager' => $idImmediateManager,
            ];

            if ($existingStaff) {
                $existingStaff->update($dataToSave);
                $updatedCount++;
            } else {
                $created = StaffModel::create($dataToSave);
                if (!empty($managerNpkRaw) && preg_replace('/[^0-9]/', '', $managerNpkRaw) == $npk) {
                    $created->update(['id_immediate_manager' => $created->id_staff]);
                }
                $insertedCount++;
            }
        }

        return [
            'inserted' => $insertedCount,
            'updated' => $updatedCount,
            'errors' => $errors,
        ];
    }

    /**
     * Parse HTML / XML table format into 2D array
     */
    private static function parseHtmlTable(string $html): array
    {
        $rows = [];
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $tableRows = $dom->getElementsByTagName('tr');
        foreach ($tableRows as $tr) {
            $row = [];
            foreach ($tr->childNodes as $cell) {
                if ($cell->nodeName === 'th' || $cell->nodeName === 'td') {
                    $row[] = trim($cell->textContent);
                }
            }
            if (!empty(array_filter($row, fn($val) => $val !== ''))) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Map arbitrary header strings to canonical keys
     */
    private static function mapHeaders(array $headers)
    {
        $map = [];
        foreach ($headers as $index => $header) {
            $h = strtolower(preg_replace('/[^a-z0-9]/', '', $header));
            if (str_contains($h, 'npk') && !str_contains($h, 'manager') && !str_contains($h, 'atasan')) {
                $map['npk'] = $index;
            } elseif (str_contains($h, 'nama') || str_contains($h, 'name')) {
                $map['nama'] = $index;
            } elseif (str_contains($h, 'lahir') || str_contains($h, 'birth') || str_contains($h, 'tgl') || str_contains($h, 'tanggal')) {
                $map['tgl_lahir'] = $index;
            } elseif (str_contains($h, 'divisi') || str_contains($h, 'division')) {
                $map['divisi'] = $index;
            } elseif (str_contains($h, 'depart') || str_contains($h, 'dept') || str_contains($h, 'bagian')) {
                $map['dept'] = $index;
            } elseif (str_contains($h, 'jabatan') || str_contains($h, 'level') || str_contains($h, 'position')) {
                $map['level'] = $index;
            } elseif (str_contains($h, 'manager') || str_contains($h, 'atasan') || str_contains($h, 'immediate')) {
                $map['manager'] = $index;
            }
        }

        if (!isset($map['npk'])) $map['npk'] = 0;
        if (!isset($map['nama'])) $map['nama'] = 1;
        if (!isset($map['tgl_lahir'])) $map['tgl_lahir'] = 2;
        if (!isset($map['divisi']) && count($headers) >= 7) {
            $map['divisi'] = 3;
            $map['dept'] = 4;
            $map['level'] = 5;
            $map['manager'] = 6;
        } else {
            if (!isset($map['dept'])) $map['dept'] = 3;
            if (!isset($map['level'])) $map['level'] = 4;
            if (!isset($map['manager'])) $map['manager'] = 5;
        }

        return $map;
    }
}
