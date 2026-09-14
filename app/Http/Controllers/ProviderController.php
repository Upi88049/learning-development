<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderModel;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $totalProviders = ProviderModel::count();
        $externalCount = ProviderModel::where('provider_type', 'External')->count();
        $internalCount = ProviderModel::where('provider_type', 'Internal')->count();

        $query = ProviderModel::query();

        // Filter tipe provider
        if ($request->filled('type')) {
            $query->where('provider_type', $request->type);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('provider_code', 'like', "%{$search}%")
                  ->orWhere('provider_name', 'like', "%{$search}%")
                  ->orWhere('provider_type', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $providers = $query->orderBy('provider_name', 'asc')->get();

        return view('dlc.provider.index', compact(
            'providers',
            'totalProviders',
            'externalCount',
            'internalCount'
        ));
    }

    public function create()
    {
        // Hitung kode otomatis untuk memudahkan pengisian
        $lastProvider = ProviderModel::orderBy('id_provider', 'desc')->first();
        $nextId = $lastProvider ? $lastProvider->id_provider + 1 : 1;
        $suggestedCode = 'PRV-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('dlc.provider.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_code' => 'required|string|max:50|unique:providers,provider_code',
            'provider_name' => 'required|string|max:255',
            'provider_type' => 'required|string|max:100',
            'pic'           => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string',
            'note'          => 'nullable|string',
        ], [
            'provider_code.required' => 'Provider code wajib diisi.',
            'provider_code.unique'   => 'Provider code sudah terdaftar.',
            'provider_name.required' => 'Provider name wajib diisi.',
            'provider_type.required' => 'Provider type wajib dipilih.',
            'email.email'            => 'Format email tidak valid.',
        ]);

        ProviderModel::create([
            'provider_code' => trim($request->provider_code),
            'provider_name' => trim($request->provider_name),
            'provider_type' => trim($request->provider_type),
            'pic'           => $request->filled('pic') ? trim($request->pic) : null,
            'phone'         => $request->filled('phone') ? trim($request->phone) : null,
            'email'         => $request->filled('email') ? trim($request->email) : null,
            'address'       => $request->filled('address') ? trim($request->address) : null,
            'note'          => $request->filled('note') ? trim($request->note) : null,
        ]);

        return redirect()->route('provider.index')->with('success', 'Provider berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $provider = ProviderModel::findOrFail($id);
        return view('dlc.provider.edit', compact('provider'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'provider_code' => 'required|string|max:50|unique:providers,provider_code,' . $id . ',id_provider',
            'provider_name' => 'required|string|max:255',
            'provider_type' => 'required|string|max:100',
            'pic'           => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string',
            'note'          => 'nullable|string',
        ], [
            'provider_code.required' => 'Provider code wajib diisi.',
            'provider_code.unique'   => 'Provider code sudah terdaftar.',
            'provider_name.required' => 'Provider name wajib diisi.',
            'provider_type.required' => 'Provider type wajib dipilih.',
            'email.email'            => 'Format email tidak valid.',
        ]);

        $provider = ProviderModel::findOrFail($id);
        $provider->update([
            'provider_code' => trim($request->provider_code),
            'provider_name' => trim($request->provider_name),
            'provider_type' => trim($request->provider_type),
            'pic'           => $request->filled('pic') ? trim($request->pic) : null,
            'phone'         => $request->filled('phone') ? trim($request->phone) : null,
            'email'         => $request->filled('email') ? trim($request->email) : null,
            'address'       => $request->filled('address') ? trim($request->address) : null,
            'note'          => $request->filled('note') ? trim($request->note) : null,
        ]);

        return redirect()->route('provider.index')->with('success', 'Provider berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $provider = ProviderModel::findOrFail($id);
        $provider->delete();

        return redirect()->route('provider.index')->with('success', 'Provider berhasil dihapus.');
    }
}
