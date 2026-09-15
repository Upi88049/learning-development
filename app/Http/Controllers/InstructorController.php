<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorModel;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $totalInstructors = InstructorModel::count();

        $query = InstructorModel::query();

        // Pencarian teks
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('instructor_code', 'like', "%{$search}%")
                  ->orWhere('instructor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $instructors = $query->orderBy('instructor_name', 'asc')->get();

        return view('dlc.instructor.index', compact('instructors', 'totalInstructors'));
    }

    public function create()
    {
        return view('dlc.instructor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'instructor_code' => 'required|string|max:50|unique:instructors,instructor_code',
            'instructor_name' => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'specialization'  => 'nullable|string|max:255',
        ], [
            'instructor_code.required' => 'Instructor code wajib diisi.',
            'instructor_code.unique'   => 'Instructor code sudah terdaftar.',
            'instructor_name.required' => 'Instructor name wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
        ]);

        InstructorModel::create([
            'instructor_code' => trim($request->instructor_code),
            'instructor_name' => trim($request->instructor_name),
            'email'           => $request->filled('email') ? trim($request->email) : null,
            'phone'           => $request->filled('phone') ? trim($request->phone) : null,
            'specialization'  => $request->filled('specialization') ? trim($request->specialization) : null,
        ]);

        return redirect()->route('instructor.index')->with('success', 'Instruktur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $instructor = InstructorModel::findOrFail($id);
        return view('dlc.instructor.edit', compact('instructor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'instructor_code' => 'required|string|max:50|unique:instructors,instructor_code,' . $id . ',id_instructor',
            'instructor_name' => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'specialization'  => 'nullable|string|max:255',
        ], [
            'instructor_code.required' => 'Instructor code wajib diisi.',
            'instructor_code.unique'   => 'Instructor code sudah terdaftar.',
            'instructor_name.required' => 'Instructor name wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
        ]);

        $instructor = InstructorModel::findOrFail($id);
        $instructor->update([
            'instructor_code' => trim($request->instructor_code),
            'instructor_name' => trim($request->instructor_name),
            'email'           => $request->filled('email') ? trim($request->email) : null,
            'phone'           => $request->filled('phone') ? trim($request->phone) : null,
            'specialization'  => $request->filled('specialization') ? trim($request->specialization) : null,
        ]);

        return redirect()->route('instructor.index')->with('success', 'Instruktur berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $instructor = InstructorModel::findOrFail($id);
        $instructor->delete();

        return redirect()->route('instructor.index')->with('success', 'Instruktur berhasil dihapus.');
    }
}
