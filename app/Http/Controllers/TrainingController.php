<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\TrainingModel;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $totalTrainings = TrainingModel::count();
        $inHouseCount = TrainingModel::where('scope_training', 'In House')->count();
        $outHouseCount = TrainingModel::where('scope_training', 'Out House')->count();

        $query = TrainingModel::query();

        // Filter Scope (In House / Out House)
        if ($request->filled('scope') && in_array($request->scope, ['In House', 'Out House'])) {
            $query->where('scope_training', $request->scope);
        }

        // Pencarian teks
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kode_training', 'like', "%{$search}%")
                  ->orWhere('nama_training', 'like', "%{$search}%")
                  ->orWhere('jenis_training', 'like', "%{$search}%")
                  ->orWhere('mandatory_training', 'like', "%{$search}%")
                  ->orWhere('gol_training', 'like', "%{$search}%")
                  ->orWhere('deskripsi_training', 'like', "%{$search}%");
            });
        }

        $trainings = $query->orderBy('jenis_training', 'asc')
            ->orderBy('nama_training', 'asc')
            ->get();

        return view('dlc.training.index', compact(
            'trainings',
            'totalTrainings',
            'inHouseCount',
            'outHouseCount'
        ));
    }

    public function create()
    {
        return view('dlc.training.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_training' => 'required|string|max:50|unique:training,kode_training',
            'scope_training' => 'required|in:In House,Out House',
            'jenis_training' => 'required|string|max:255',
            'nama_training' => 'required|string|max:255',
            'mandatory_training' => 'nullable|string|max:255',
            'gol_training' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'deskripsi_training' => 'nullable|string',
        ], [
            'kode_training.required' => 'Kode training wajib diisi.',
            'kode_training.unique' => 'Kode training sudah terdaftar di sistem.',
            'scope_training.required' => 'Scope training wajib dipilih.',
            'scope_training.in' => 'Scope training hanya boleh bernilai In House atau Out House.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 5MB.',
        ]);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $gambarName = 'training_' . time() . '_' . Str::random(8) . '.' . $extension;
            $destination = public_path('uploads/training');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $gambarName);
        }

        TrainingModel::create([
            'kode_training' => trim($request->kode_training),
            'jenis_training' => $request->jenis_training,
            'nama_training' => $request->nama_training,
            'scope_training' => $request->scope_training,
            'mandatory_training' => $request->filled('mandatory_training') ? trim($request->mandatory_training) : null,
            'gol_training' => $request->filled('gol_training') ? trim($request->gol_training) : null,
            'gambar' => $gambarName,
            'deskripsi_training' => $request->filled('deskripsi_training') ? trim($request->deskripsi_training) : null,
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
            'kode_training' => 'required|string|max:50|unique:training,kode_training,' . $id . ',id_training',
            'scope_training' => 'required|in:In House,Out House',
            'jenis_training' => 'required|string|max:255',
            'nama_training' => 'required|string|max:255',
            'mandatory_training' => 'nullable|string|max:255',
            'gol_training' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'deskripsi_training' => 'nullable|string',
            'hapus_gambar' => 'nullable|boolean',
        ], [
            'kode_training.required' => 'Kode training wajib diisi.',
            'kode_training.unique' => 'Kode training sudah terdaftar di sistem.',
            'scope_training.required' => 'Scope training wajib dipilih.',
            'scope_training.in' => 'Scope training hanya boleh bernilai In House atau Out House.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 5MB.',
        ]);

        $training = TrainingModel::findOrFail($id);
        $gambarName = $training->gambar;
        $destination = public_path('uploads/training');

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $newGambarName = 'training_' . time() . '_' . Str::random(8) . '.' . $extension;
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            if ($gambarName && file_exists($destination . '/' . $gambarName)) {
                @unlink($destination . '/' . $gambarName);
            }
            $file->move($destination, $newGambarName);
            $gambarName = $newGambarName;
        } elseif ($request->boolean('hapus_gambar')) {
            if ($gambarName && file_exists($destination . '/' . $gambarName)) {
                @unlink($destination . '/' . $gambarName);
            }
            $gambarName = null;
        }

        $training->update([
            'kode_training' => trim($request->kode_training),
            'jenis_training' => $request->jenis_training,
            'nama_training' => $request->nama_training,
            'scope_training' => $request->scope_training,
            'mandatory_training' => $request->filled('mandatory_training') ? trim($request->mandatory_training) : null,
            'gol_training' => $request->filled('gol_training') ? trim($request->gol_training) : null,
            'gambar' => $gambarName,
            'deskripsi_training' => $request->filled('deskripsi_training') ? trim($request->deskripsi_training) : null,
        ]);

        return redirect()->route('training.index')->with('success', 'Training berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $training = TrainingModel::findOrFail($id);
        if ($training->gambar && file_exists(public_path('uploads/training/' . $training->gambar))) {
            @unlink(public_path('uploads/training/' . $training->gambar));
        }
        // Hapus relasi di staff_training jika ada
        $training->staffTrainings()->delete();
        $training->delete();

        return redirect()->route('training.index')->with('success', 'Training berhasil dihapus.');
    }
}
