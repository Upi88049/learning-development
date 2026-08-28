<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DivisiModel;

class DivisiController extends Controller
{
    public function index()
    {
        $divisi = DivisiModel::withCount('departments')
            ->orderBy('nama_divisi', 'asc')
            ->get();

        return view('dlc.divisi.index', compact('divisi'));
    }

    public function create()
    {
        return view('dlc.divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi',
        ]);

        DivisiModel::create([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $divisi = DivisiModel::findOrFail($id);
        return view('dlc.divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi,' . $id . ',id_divisi',
        ]);

        $divisi = DivisiModel::findOrFail($id);
        $divisi->update([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $divisi = DivisiModel::withCount('departments')->findOrFail($id);

        if ($divisi->departments_count > 0) {
            return redirect()->route('divisi.index')->with('error', 'Divisi tidak dapat dihapus karena masih memiliki departemen terkait.');
        }

        $divisi->delete();

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
