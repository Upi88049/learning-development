<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VenueModel;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $totalVenues = VenueModel::count();
        $internalCount = VenueModel::where('venue_type', 'Internal')->count();
        $externalCount = VenueModel::where('venue_type', 'External')->count();

        $query = VenueModel::query();

        // Filter Tipe Venue (Internal / External)
        if ($request->filled('type') && in_array($request->type, ['Internal', 'External'])) {
            $query->where('venue_type', $request->type);
        }

        // Pencarian teks
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('venue_code', 'like', "%{$search}%")
                  ->orWhere('venue_name', 'like', "%{$search}%")
                  ->orWhere('venue_type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $venues = $query->orderBy('venue_code', 'asc')->get();

        return view('dlc.venue.index', compact(
            'venues',
            'totalVenues',
            'internalCount',
            'externalCount'
        ));
    }

    public function create()
    {
        // Hitung kode otomatis untuk memudahkan pengisian
        $lastVenue = VenueModel::orderBy('id_venue', 'desc')->first();
        $nextId = $lastVenue ? $lastVenue->id_venue + 1 : 1;
        $suggestedCode = 'RT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('dlc.venue.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'venue_code'  => 'required|string|max:50|unique:venues,venue_code',
            'venue_name'  => 'required|string|max:255',
            'venue_type'  => 'required|in:Internal,External',
        ], [
            'venue_code.required' => 'Venue code wajib diisi.',
            'venue_code.unique'   => 'Venue code sudah terdaftar.',
            'venue_name.required' => 'Venue name wajib diisi.',
            'venue_type.required' => 'Venue type wajib dipilih.',
            'venue_type.in'       => 'Venue type harus bernilai Internal atau External.',
        ]);

        VenueModel::create([
            'venue_code'  => trim($request->venue_code),
            'venue_name'  => trim($request->venue_name),
            'venue_type'  => $request->venue_type,
        ]);

        return redirect()->route('venue.index')->with('success', 'Venue berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $venue = VenueModel::findOrFail($id);
        return view('dlc.venue.edit', compact('venue'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'venue_code'  => 'required|string|max:50|unique:venues,venue_code,' . $id . ',id_venue',
            'venue_name'  => 'required|string|max:255',
            'venue_type'  => 'required|in:Internal,External',
        ], [
            'venue_code.required' => 'Venue code wajib diisi.',
            'venue_code.unique'   => 'Venue code sudah terdaftar.',
            'venue_name.required' => 'Venue name wajib diisi.',
            'venue_type.required' => 'Venue type wajib dipilih.',
            'venue_type.in'       => 'Venue type harus bernilai Internal atau External.',
        ]);

        $venue = VenueModel::findOrFail($id);
        $venue->update([
            'venue_code'  => trim($request->venue_code),
            'venue_name'  => trim($request->venue_name),
            'venue_type'  => $request->venue_type,
        ]);

        return redirect()->route('venue.index')->with('success', 'Venue berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $venue = VenueModel::findOrFail($id);
        $venue->delete();

        return redirect()->route('venue.index')->with('success', 'Venue berhasil dihapus.');
    }
}
