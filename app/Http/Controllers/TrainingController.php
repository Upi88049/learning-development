<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingModel;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = TrainingModel::orderBy('jenis_training', 'asc')
            ->orderBy('nama_training', 'asc')
            ->get();

        return view('dlc.training.index', compact('trainings'));
    }

    public function create()
    {
        return view('dlc.training.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_training' => 'required|string|max:255',
            'nama_training' => 'required|string|max:255',
            'mandatory_training' => 'nullable|string|max:255',
            'gol_training' => 'nullable|string|max:255',
        ]);

        TrainingModel::create([
            'jenis_training' => $request->jenis_training,
            'nama_training' => $request->nama_training,
            'mandatory_training' => $request->mandatory_training,
            'gol_training' => $request->gol_training,
        ]);

        return redirect()->route('training.index')->with('success', 'Training berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $training = TrainingModel::findOrFail($id);
        return view('dlc.training.edit', compact('training'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_training' => 'required|string|max:255',
            'nama_training' => 'required|string|max:255',
            'mandatory_training' => 'nullable|string|max:255',
            'gol_training' => 'nullable|string|max:255',
        ]);

        $training = TrainingModel::findOrFail($id);
        $training->update([
            'jenis_training' => $request->jenis_training,
            'nama_training' => $request->nama_training,
            'mandatory_training' => $request->mandatory_training,
            'gol_training' => $request->gol_training,
        ]);

        return redirect()->route('training.index')->with('success', 'Training berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $training = TrainingModel::findOrFail($id);
        // Hapus relasi di staff_training jika ada
        $training->staffTrainings()->delete();
        $training->delete();

        return redirect()->route('training.index')->with('success', 'Training berhasil dihapus.');
    }
}
