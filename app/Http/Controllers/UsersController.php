<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\StaffModel;
use App\Models\StaffTrainingModel;
use App\Models\DepartmentModel;
use App\Models\DivisiModel;
use App\Models\LevelJabatanModel;
use App\Models\EmailConfigModel;
use App\Helpers\StaffSpreadsheetHelper;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $sessionUser = session('user');
        
        if ($sessionUser) {
            $userStaff = StaffModel::with('department')->find($sessionUser->id_staff);
            $departmentName = $userStaff && $userStaff->department ? $userStaff->department->nama_department : 'Department';
            
            // Filter staff list to only show subordinates AND the logged-in immediate manager, ordered by department name
            $staff = StaffModel::with(['department', 'levelJabatan', 'immediateManager'])
                ->leftJoin('department', 'staff.id_department', '=', 'department.id_department')
                ->where(function ($query) use ($sessionUser) {
                    $query->where('staff.id_immediate_manager', $sessionUser->id_staff)
                          ->orWhere('staff.id_staff', $sessionUser->id_staff);
                })
                ->orderBy('department.nama_department', 'asc')
                ->select('staff.*')
                ->get();
        } else {
            $departmentName = 'Department';
            $staff = StaffModel::with(['department', 'levelJabatan', 'immediateManager'])
                ->leftJoin('department', 'staff.id_department', '=', 'department.id_department')
                ->orderBy('department.nama_department', 'asc')
                ->select('staff.*')
                ->get();
        }

        $staffIds = $staff->pluck('id_staff')->toArray();

        // Ambil data counts untuk 3 metrik
        $allStaffTrainings = StaffTrainingModel::whereIn('id_staff', $staffIds)->get();

        $totalPermintaan = $allStaffTrainings->whereIn('id_status', [2, 4])->count();
        $totalTerlaksana = $allStaffTrainings->where('id_status', 1)->count();
        $totalKetidakhadiran = $allStaffTrainings->where('id_status', 3)->count();

        return view('user.user', compact(
            'staff',
            'departmentName',
            'totalPermintaan',
            'totalTerlaksana',
            'totalKetidakhadiran'
        ));
    }

    public function dlc(Request $request)
    {
        $selectedDivisi = $request->query('divisi');
        $selectedDepartment = $request->query('department');

        $divisiList = DivisiModel::orderBy('nama_divisi', 'asc')->get();
        $departments = DepartmentModel::orderBy('nama_department', 'asc')->get();

        $query = StaffModel::with(['divisi', 'department', 'levelJabatan', 'immediateManager'])
            ->leftJoin('divisi', 'staff.id_divisi', '=', 'divisi.id_divisi')
            ->leftJoin('department', 'staff.id_department', '=', 'department.id_department')
            ->orderByRaw('COALESCE(divisi.nama_divisi, "ZZZ") ASC')
            ->orderByRaw('COALESCE(department.nama_department, "ZZZ") ASC')
            ->orderBy('staff.nama_staff', 'asc')
            ->select('staff.*');

        if ($selectedDivisi && $selectedDivisi !== 'all') {
            if ($selectedDivisi === 'none') {
                $query->whereNull('staff.id_divisi');
            } else {
                $query->where(function ($q) use ($selectedDivisi) {
                    $q->where('divisi.nama_divisi', $selectedDivisi)
                      ->orWhere('staff.id_divisi', $selectedDivisi);
                });
            }
        }

        if ($selectedDepartment && $selectedDepartment !== 'all') {
            if ($selectedDepartment === 'none') {
                $query->whereNull('staff.id_department');
            } else {
                $query->where(function ($q) use ($selectedDepartment) {
                    $q->where('department.nama_department', $selectedDepartment)
                      ->orWhere('staff.id_department', $selectedDepartment);
                });
            }
        }

        $staff = $query->get();
        $masterTrainings = UserModel::orderBy('id_training', 'asc')->get();

        return view('dlc.immediatemanager', compact('staff', 'divisiList', 'departments', 'selectedDivisi', 'selectedDepartment', 'masterTrainings'));
    }

    public function detail($id_staff)
    {
        $staff = StaffModel::with(['department', 'levelJabatan', 'immediateManager'])->findOrFail($id_staff);
        $trainings = UserModel::all();

        // Ambil semua record status training milik staff ini, di-key berdasarkan id_training
        $staffTrainings = StaffTrainingModel::where('id_staff', $id_staff)
            ->get()
            ->keyBy('id_training');

        if (session('role') === 'DLC') {
            return view('dlc.staffdetail', compact('staff', 'trainings', 'staffTrainings'));
        }

        $isTnaActive = EmailConfigModel::isTnaActive();
        $tnaStartDate = EmailConfigModel::getTnaStartDate();
        $tnaEndDate = EmailConfigModel::getTnaEndDate();

        return view('user.userdetail', compact('staff', 'trainings', 'staffTrainings', 'isTnaActive', 'tnaStartDate', 'tnaEndDate'));
    }

    public function create()
    {
        $divisi = DivisiModel::orderBy('nama_divisi', 'asc')->get();
        $departments = DepartmentModel::orderBy('nama_department', 'asc')->get();
        $levels = LevelJabatanModel::all();
        $managers = StaffModel::orderBy('nama_staff', 'asc')->get();
        return view('user.create', compact('divisi', 'departments', 'levels', 'managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npk_staff' => 'required|integer|unique:staff,npk_staff',
            'nama_staff' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'id_divisi' => 'nullable|integer',
            'id_department' => 'nullable|integer',
            'id_jabatan_staff' => 'required|integer',
            'id_immediate_manager' => 'nullable|integer',
        ]);

        $idDivisi = $request->id_divisi ?: null;
        $idDepartment = $request->id_department ?: null;

        // Jika divisi kosong tapi department diisi, ambil divisi dari department jika ada
        if (!$idDivisi && $idDepartment) {
            $dept = DepartmentModel::find($idDepartment);
            if ($dept && $dept->id_divisi) {
                $idDivisi = $dept->id_divisi;
            }
        }

        StaffModel::create([
            'npk_staff' => $request->npk_staff,
            'nama_staff' => $request->nama_staff,
            'tanggal_lahir' => $request->tanggal_lahir,
            'id_divisi' => $idDivisi,
            'id_department' => $idDepartment,
            'id_jabatan_staff' => $request->id_jabatan_staff,
            'id_immediate_manager' => $request->id_immediate_manager,
        ]);

        return redirect()->route('member-list')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function editStaff($id)
    {
        $staff = StaffModel::findOrFail($id);
        $divisi = DivisiModel::orderBy('nama_divisi', 'asc')->get();
        $departments = DepartmentModel::orderBy('nama_department', 'asc')->get();
        $levels = LevelJabatanModel::all();
        $managers = StaffModel::where('id_staff', '!=', $id)->orderBy('nama_staff', 'asc')->get();
        return view('dlc.staff.edit', compact('staff', 'divisi', 'departments', 'levels', 'managers'));
    }

    public function updateStaff(Request $request, $id)
    {
        $request->validate([
            'npk_staff' => 'required|integer|unique:staff,npk_staff,' . $id . ',id_staff',
            'nama_staff' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'id_divisi' => 'nullable|integer',
            'id_department' => 'nullable|integer',
            'id_jabatan_staff' => 'required|integer',
            'id_immediate_manager' => 'nullable|integer',
        ]);

        $idDivisi = $request->id_divisi ?: null;
        $idDepartment = $request->id_department ?: null;

        if (!$idDivisi && $idDepartment) {
            $dept = DepartmentModel::find($idDepartment);
            if ($dept && $dept->id_divisi) {
                $idDivisi = $dept->id_divisi;
            }
        }

        $staff = StaffModel::findOrFail($id);
        $staff->update([
            'npk_staff' => $request->npk_staff,
            'nama_staff' => $request->nama_staff,
            'tanggal_lahir' => $request->tanggal_lahir,
            'id_divisi' => $idDivisi,
            'id_department' => $idDepartment,
            'id_jabatan_staff' => $request->id_jabatan_staff,
            'id_immediate_manager' => $request->id_immediate_manager,
        ]);

        return redirect()->route('member-list')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroyStaff($id)
    {
        $staff = StaffModel::findOrFail($id);

        // Hapus relasi staff training
        StaffTrainingModel::where('id_staff', $id)->delete();

        // Nullify subordinates' immediate manager
        StaffModel::where('id_immediate_manager', $id)->update(['id_immediate_manager' => null]);

        $staff->delete();

        return redirect()->route('member-list')->with('success', 'Staff berhasil dihapus.');
    }

    public function bulkDestroyStaff(Request $request)
    {
        $ids = $request->input('staff_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('member-list')->with('error', 'Tidak ada staff yang dipilih untuk dihapus.');
        }

        // Hapus relasi staff training
        StaffTrainingModel::whereIn('id_staff', $ids)->delete();

        // Nullify subordinates' immediate manager
        StaffModel::whereIn('id_immediate_manager', $ids)->update(['id_immediate_manager' => null]);

        $deletedCount = StaffModel::whereIn('id_staff', $ids)->delete();

        return redirect()->route('member-list')->with('success', $deletedCount . ' data staff berhasil dihapus.');
    }

    public function exportStaff(Request $request)
    {
        $selectedDivisi = $request->query('divisi');
        $selectedDepartment = $request->query('department');

        $query = StaffModel::with(['divisi', 'department', 'levelJabatan', 'immediateManager'])
            ->leftJoin('divisi', 'staff.id_divisi', '=', 'divisi.id_divisi')
            ->leftJoin('department', 'staff.id_department', '=', 'department.id_department')
            ->orderByRaw('COALESCE(divisi.nama_divisi, "ZZZ") ASC')
            ->orderByRaw('COALESCE(department.nama_department, "ZZZ") ASC')
            ->orderBy('staff.nama_staff', 'asc')
            ->select('staff.*');

        if ($selectedDivisi && $selectedDivisi !== 'all') {
            if ($selectedDivisi === 'none') {
                $query->whereNull('staff.id_divisi');
            } else {
                $query->where(function ($q) use ($selectedDivisi) {
                    $q->where('divisi.nama_divisi', $selectedDivisi)
                      ->orWhere('staff.id_divisi', $selectedDivisi);
                });
            }
        }

        if ($selectedDepartment && $selectedDepartment !== 'all') {
            if ($selectedDepartment === 'none') {
                $query->whereNull('staff.id_department');
            } else {
                $query->where(function ($q) use ($selectedDepartment) {
                    $q->where('department.nama_department', $selectedDepartment)
                      ->orWhere('staff.id_department', $selectedDepartment);
                });
            }
        }

        $staff = $query->get();

        return StaffSpreadsheetHelper::exportStaff($staff, $selectedDepartment, $selectedDivisi);
    }

    public function templateStaff()
    {
        return StaffSpreadsheetHelper::downloadTemplate();
    }

    public function importStaff(Request $request)
    {
        $request->validate([
            'file_import' => 'required|file|max:5120',
        ], [
            'file_import.required' => 'Silakan pilih berkas Excel / CSV untuk diunggah.',
        ]);

        try {
            $file = $request->file('file_import');
            $result = StaffSpreadsheetHelper::importFromFile($file);

            $msg = "Import berhasil diproses! ({$result['inserted']} data baru ditambahkan, {$result['updated']} data diperbarui).";
            if (!empty($result['errors'])) {
                $msg .= ' Catatan: ' . implode(' ', array_slice($result['errors'], 0, 3));
            }

            return redirect()->route('member-list')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->route('member-list')->with('error', 'Gagal memproses import: ' . $e->getMessage());
        }
    }

    public function templateStaffTraining()
    {
        return StaffSpreadsheetHelper::downloadTrainingHistoryTemplate();
    }

    public function importStaffTraining(Request $request)
    {
        $request->validate([
            'file_import_training' => 'required|file|max:5120',
        ], [
            'file_import_training.required' => 'Silakan pilih berkas Excel / CSV history training untuk diunggah.',
        ]);

        try {
            $file = $request->file('file_import_training');
            $result = StaffSpreadsheetHelper::importStaffTrainingHistory($file);

            $msg = "Import history training berhasil! ({$result['staff_count']} staff diperbarui, {$result['training_records']} status training tercatat).";
            if (!empty($result['errors'])) {
                $msg .= ' Catatan: ' . implode(' ', array_slice($result['errors'], 0, 3));
            }

            return redirect()->route('member-list')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->route('member-list')->with('error', 'Gagal memproses import history training: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|integer',
            'id_training' => 'required|integer',
            'id_status' => 'required|integer',
        ]);

        $role = session('role');

        if ($role === 'Immediate Manager') {
            // Cek apakah periode TNA aktif
            if (!EmailConfigModel::isTnaActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode pengisian TNA sedang tidak aktif atau telah ditutup. Anda hanya dapat melihat data.',
                ], 403);
            }

            // Immediate Manager hanya boleh memilih status 4 (In House Training)
            if ((int)$request->id_status !== 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Immediate Manager hanya diizinkan memilih status In House Training.',
                ], 403);
            }
        }

        StaffTrainingModel::updateOrCreate(
            [
                'id_staff' => $request->id_staff,
                'id_training' => $request->id_training,
            ],
            [
                'id_status' => $request->id_status,
            ]
        );

        return response()->json(['success' => true]);
    }

    private function getTrainingDataByStatus($statusIds)
    {
        $sessionUser = session('user');
        
        if ($sessionUser) {
            $staffIds = StaffModel::where('id_immediate_manager', $sessionUser->id_staff)
                ->orWhere('id_staff', $sessionUser->id_staff)
                ->pluck('id_staff')
                ->toArray();
        } else {
            $staffIds = StaffModel::pluck('id_staff')->toArray();
        }

        $statusArray = is_array($statusIds) ? $statusIds : [$statusIds];

        $trainingsCollection = StaffTrainingModel::with(['training', 'staff'])
            ->whereIn('id_staff', $staffIds)
            ->whereIn('id_status', $statusArray)
            ->get();

        $grouped = [];
        foreach ($trainingsCollection as $st) {
            if (!$st->training) continue;
            $trainingId = $st->id_training;
            if (!isset($grouped[$trainingId])) {
                $grouped[$trainingId] = [
                    'id_training' => $trainingId,
                    'jenis_training' => $st->training->jenis_training ?? '-',
                    'nama_training' => $st->training->nama_training ?? '-',
                    'jumlah' => 0,
                    'staff_list' => [],
                ];
            }
            $grouped[$trainingId]['jumlah']++;
            if ($st->staff) {
                $grouped[$trainingId]['staff_list'][] = [
                    'id_staff' => $st->staff->id_staff,
                    'npk_staff' => $st->staff->npk_staff,
                    'nama_staff' => $st->staff->nama_staff,
                ];
            }
        }

        $result = array_values($grouped);

        // Sort by jenis_training, then by nama_training
        usort($result, function ($a, $b) {
            $cmp = strcmp($a['jenis_training'], $b['jenis_training']);
            if ($cmp === 0) {
                return strcmp($a['nama_training'], $b['nama_training']);
            }
            return $cmp;
        });

        return $result;
    }

    public function permintaan()
    {
        $trainings = $this->getTrainingDataByStatus([2, 4]);
        return view('user.permintaan', compact('trainings'));
    }

    public function terlaksana()
    {
        $trainings = $this->getTrainingDataByStatus([1]);
        return view('user.terlaksana', compact('trainings'));
    }

    public function tidakhadir()
    {
        $trainings = $this->getTrainingDataByStatus([3]);
        return view('user.tidakhadir', compact('trainings'));
    }
}
