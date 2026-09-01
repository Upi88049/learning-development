<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentModel;
use App\Models\DivisiModel;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = DepartmentModel::with('divisi')
            ->withCount('staff')
            ->orderBy('nama_department', 'asc')
            ->get();

        return view('dlc.department.index', compact('departments'));
    }

    public function create()
    {
        $divisi = DivisiModel::orderBy('nama_divisi', 'asc')->get();
        return view('dlc.department.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_divisi' => 'nullable|integer|exists:divisi,id_divisi',
            'nama_department' => 'required|string|max:255|unique:department,nama_department',
        ]);

        DepartmentModel::create([
            'id_divisi' => $request->id_divisi ?: null,
            'nama_department' => $request->nama_department,
        ]);

        return redirect()->route('department.index')->with('success', 'Department berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $department = DepartmentModel::findOrFail($id);
        $divisi = DivisiModel::orderBy('nama_divisi', 'asc')->get();
        return view('dlc.department.edit', compact('department', 'divisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_divisi' => 'nullable|integer|exists:divisi,id_divisi',
            'nama_department' => 'required|string|max:255|unique:department,nama_department,' . $id . ',id_department',
        ]);

        $department = DepartmentModel::findOrFail($id);
        $department->update([
            'id_divisi' => $request->id_divisi ?: null,
            'nama_department' => $request->nama_department,
        ]);

        return redirect()->route('department.index')->with('success', 'Department berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $department = DepartmentModel::withCount('staff')->findOrFail($id);

        if ($department->staff_count > 0) {
            return redirect()->route('department.index')->with('error', 'Department tidak dapat dihapus karena masih memiliki data staff terkait.');
        }

        $department->delete();

        return redirect()->route('department.index')->with('success', 'Department berhasil dihapus.');
    }
}
