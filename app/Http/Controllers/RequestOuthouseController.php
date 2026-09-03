<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestOuthouseModel;
use App\Models\StaffModel;

class RequestOuthouseController extends Controller
{
    /**
     * Store new Out House Training Request from Immediate Manager
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|integer|exists:staff,id_staff',
            'judul_training' => 'required|string|max:255',
            'deskripsi_training' => 'required|string',
            'reason' => 'required|string',
        ], [
            'id_staff.required' => 'Staff tujuan wajib dipilih.',
            'judul_training.required' => 'Judul training wajib diisi.',
            'deskripsi_training.required' => 'Deskripsi training wajib diisi.',
            'reason.required' => 'Reason / alasan training wajib diisi.',
        ]);

        $noRequest = RequestOuthouseModel::generateNoRequest();
        $idManager = session('user') ? session('user')->id_staff : null;

        // If manager not from session, fallback to staff's registered immediate manager
        if (!$idManager) {
            $staff = StaffModel::find($request->id_staff);
            $idManager = $staff ? $staff->id_immediate_manager : null;
        }

        $outhouse = RequestOuthouseModel::create([
            'no_request' => $noRequest,
            'id_staff' => $request->id_staff,
            'id_immediate_manager' => $idManager,
            'judul_training' => $request->judul_training,
            'deskripsi_training' => $request->deskripsi_training,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', "Request Training Out House berhasil diajukan! No. Request: {$noRequest}");
    }

    /**
     * Update Out House Training Request by Immediate Manager
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_training' => 'required|string|max:255',
            'deskripsi_training' => 'required|string',
            'reason' => 'required|string',
        ], [
            'judul_training.required' => 'Judul training wajib diisi.',
            'deskripsi_training.required' => 'Deskripsi training wajib diisi.',
            'reason.required' => 'Reason / alasan training wajib diisi.',
        ]);

        $outhouse = RequestOuthouseModel::findOrFail($id);
        $outhouse->update([
            'judul_training' => $request->judul_training,
            'deskripsi_training' => $request->deskripsi_training,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', "Request Training Out House ({$outhouse->no_request}) berhasil diperbarui.");
    }

    /**
     * Delete Out House Training Request by Immediate Manager
     */
    public function destroy($id)
    {
        $outhouse = RequestOuthouseModel::findOrFail($id);
        $noRequest = $outhouse->no_request;
        $outhouse->delete();

        return redirect()->back()->with('success', "Request Training Out House ({$noRequest}) berhasil dihapus.");
    }

    /**
     * Display all Out House Training Requests for DLC
     */
    public function index(Request $request)
    {
        $selectedStatus = $request->query('status', 'all');
        $search = $request->query('search');

        $query = RequestOuthouseModel::with([
            'staff.divisi',
            'staff.department',
            'immediateManager'
        ])->orderBy('id_request_outhouse', 'desc');

        if ($selectedStatus && $selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_request', 'like', "%{$search}%")
                  ->orWhere('judul_training', 'like', "%{$search}%")
                  ->orWhere('deskripsi_training', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('staff', function ($sq) use ($search) {
                      $sq->where('nama_staff', 'like', "%{$search}%")
                         ->orWhere('npk_staff', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->get();

        // Statistics counts
        $totalAll = RequestOuthouseModel::count();
        $countPending = RequestOuthouseModel::where('status', 'Pending')->count();
        $countVerified = RequestOuthouseModel::where('status', 'Verified by DLC')->count();
        $countApproved = RequestOuthouseModel::where('status', 'Approve')->count();
        $countRejected = RequestOuthouseModel::where('status', 'Rejected With Reason')->count();

        return view('dlc.outhouse.index', compact(
            'requests',
            'selectedStatus',
            'search',
            'totalAll',
            'countPending',
            'countVerified',
            'countApproved',
            'countRejected'
        ));
    }

    /**
     * Update Status of Out House Training Request by DLC
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Verified by DLC,Approve,Rejected With Reason',
            'alasan_reject' => 'required_if:status,Rejected With Reason|nullable|string',
        ], [
            'status.required' => 'Pilih status yang valid.',
            'alasan_reject.required_if' => 'Alasan reject wajib diisi jika status Rejected With Reason.',
        ]);

        $outhouse = RequestOuthouseModel::findOrFail($id);
        $outhouse->update([
            'status' => $request->status,
            'alasan_reject' => $request->status === 'Rejected With Reason' ? $request->alasan_reject : null,
        ]);

        return redirect()->back()->with('success', "Status Request {$outhouse->no_request} berhasil diubah menjadi: {$request->status}.");
    }

    /**
     * Delete Out House Training Request by DLC
     */
    public function destroyDlc($id)
    {
        $outhouse = RequestOuthouseModel::findOrFail($id);
        $noRequest = $outhouse->no_request;
        $outhouse->delete();

        return redirect()->back()->with('success', "Request Training Out House ({$noRequest}) berhasil dihapus oleh DLC.");
    }
}
