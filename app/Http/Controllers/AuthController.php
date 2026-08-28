<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\StaffModel;
use App\Models\DlcAccountModel;

class AuthController extends Controller
{
    public function index()
    {
        if (session()->has('user')) {
            $role = session('role');
            if ($role === 'DLC') {
                return redirect()->route('dashboarddlc');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Login Immediate Manager via NPK
     */
    public function login(Request $request)
    {
        $request->validate([
            'npk' => 'required',
        ], [
            'npk.required' => 'Silakan masukkan NPK Anda.',
        ]);

        $staff = StaffModel::with(['department', 'levelJabatan'])->where('npk_staff', $request->npk)->first();

        if (!$staff) {
            return redirect()->back()->with('error', 'NPK tidak ditemukan!')->withInput();
        }

        // Cek Hak Akses: hanya Immediate Manager yang boleh login via NPK
        $isImmediateManager = $staff->isImmediateManager();

        if (!$isImmediateManager) {
            return redirect()->back()->with('error', 'Maaf, NPK Anda tidak memiliki akses untuk login. Hanya Immediate Manager yang diizinkan login menggunakan NPK.')->withInput();
        }

        // Simpan session pengguna
        session(['user' => $staff]);
        session(['role' => 'Immediate Manager']);

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $staff->nama_staff);
    }

    /**
     * Login DLC via Username + Password
     */
    public function loginDlc(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Silakan masukkan username.',
            'password.required' => 'Silakan masukkan password.',
        ]);

        $dlcAccount = DlcAccountModel::where('username', $request->username)->first();

        if (!$dlcAccount || !Hash::check($request->password, $dlcAccount->password)) {
            return redirect()->back()->with('error', 'Username atau password DLC salah!')->withInput();
        }

        // Simpan session DLC
        session(['user' => (object) [
            'id' => $dlcAccount->id,
            'nama_staff' => $dlcAccount->nama,
            'username' => $dlcAccount->username,
        ]]);
        session(['role' => 'DLC']);

        return redirect()->route('dashboarddlc')->with('success', 'Selamat datang, ' . $dlcAccount->nama);
    }

    public function logout()
    {
        session()->forget(['user', 'role']);
        session()->flush();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
