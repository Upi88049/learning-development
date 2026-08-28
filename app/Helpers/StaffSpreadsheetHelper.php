<?php

namespace App\Helpers;

use App\Models\StaffModel;
use App\Models\DepartmentModel;
use App\Models\DivisiModel;
use App\Models\LevelJabatanModel;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class StaffSpreadsheetHelper
{
    /**
     * Generate & download genuine CSV file configured for Microsoft Excel without security warning
     */
    public static function exportStaff($staffList, $departmentName = null)
    {
        $suffix = $departmentName ? '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $departmentName) : '';
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
            // Instruct Excel explicitly what delimiter to use (auto splits columns cleanly)
            fputs($handle, "sep=,\r\n");

            // Header row
            fputcsv($handle, [
                'NPK',
                'Nama Staff',
                'Tanggal Lahir (YYYY-MM-DD)',
                'Department',
                'Level Jabatan',
                'NPK Immediate Manager',
            ]);

            foreach ($staffList as $s) {
                fputcsv($handle, [
                    $s->npk_staff,
                    $s->nama_staff,
                    $s->tanggal_lahir ? $s->tanggal_lahir->format('Y-m-d') : '',
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
     * Download template format Excel / CSV
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
            fputs($handle, "sep=,\r\n");

            // Header row
            fputcsv($handle, [
                'NPK',
                'Nama Staff',
                'Tanggal Lahir (YYYY-MM-DD)',
                'Department',
                'Level Jabatan',
                'NPK Immediate Manager',
            ]);

            // Sample rows
            fputcsv($handle, [
                '11990999',
                'Contoh Budi Santoso',
                '1995-08-17',
                'GA & IR',
                'SF',
                '11990935',
            ]);

            fputcsv($handle, [
                '11990888',
                'Contoh Siti Rahma',
                '1998-12-25',
                'Learning & Development',
                'SF',
                '',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process import from UploadedFile or file path
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
     * Parse raw string content and upsert staff data
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
        $departments = DepartmentModel::all();
        $deptByName = [];
        foreach ($departments as $d) {
            $deptByName[strtolower(trim($d->nama_department))] = $d->id_department;
        }

        $levels = LevelJabatanModel::all();
        $levelByCode = [];
        foreach ($levels as $l) {
            $levelByCode[strtolower(trim($l->kode_level_jabatan))] = $l->id_level_jabatan;
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $errors = [];

        // Default Divisi for newly created departments
        $defaultDivisiId = DivisiModel::first()?->id_divisi ?? 1;

        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2;

            $npk = isset($headerMap['npk']) && isset($row[$headerMap['npk']]) ? trim($row[$headerMap['npk']]) : '';
            $nama = isset($headerMap['nama']) && isset($row[$headerMap['nama']]) ? trim($row[$headerMap['nama']]) : '';
            $tglLahirRaw = isset($headerMap['tgl_lahir']) && isset($row[$headerMap['tgl_lahir']]) ? trim($row[$headerMap['tgl_lahir']]) : '';
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

            // Resolve department
            $idDepartment = null;
            if (!empty($deptRaw)) {
                if (is_numeric($deptRaw) && $departments->contains('id_department', (int)$deptRaw)) {
                    $idDepartment = (int)$deptRaw;
                } else {
                    $lookup = strtolower(trim($deptRaw));
                    if (isset($deptByName[$lookup])) {
                        $idDepartment = $deptByName[$lookup];
                    } else {
                        // Auto-create department if not exists
                        $newDept = DepartmentModel::create([
                            'nama_department' => trim($deptRaw),
                            'id_divisi' => $defaultDivisiId,
                        ]);
                        $idDepartment = $newDept->id_department;
                        $deptByName[$lookup] = $idDepartment;
                    }
                }
            }
            if (!$idDepartment) {
                $idDepartment = $departments->first()?->id_department ?? 1;
            }

            // Resolve level jabatan
            $idJabatanStaff = null;
            if (!empty($levelRaw)) {
                if (is_numeric($levelRaw) && $levels->contains('id_level_jabatan', (int)$levelRaw)) {
                    $idJabatanStaff = (int)$levelRaw;
                } else {
                    $lookup = strtolower(trim($levelRaw));
                    if (isset($levelByCode[$lookup])) {
                        $idJabatanStaff = $levelByCode[$lookup];
                    } else {
                        // Auto-create level jabatan if not exists
                        $newLevel = LevelJabatanModel::create([
                            'kode_level_jabatan' => strtoupper(trim($levelRaw)),
                            'keterangan' => strtoupper(trim($levelRaw)),
                        ]);
                        $idJabatanStaff = $newLevel->id_level_jabatan;
                        $levelByCode[$lookup] = $idJabatanStaff;
                    }
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
                'id_department' => $idDepartment,
                'id_jabatan_staff' => $idJabatanStaff,
                'id_immediate_manager' => $idImmediateManager,
            ];

            if ($existingStaff) {
                $existingStaff->update($dataToSave);
                $updatedCount++;
            } else {
                $created = StaffModel::create($dataToSave);
                // If the manager NPK was this staff's own NPK, self-reference manager
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
        if (!isset($map['dept'])) $map['dept'] = 3;
        if (!isset($map['level'])) $map['level'] = 4;
        if (!isset($map['manager'])) $map['manager'] = 5;

        return $map;
    }
}
