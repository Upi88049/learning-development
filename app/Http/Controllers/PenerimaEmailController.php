<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailConfigModel;

class PenerimaEmailController extends Controller
{
    public function index()
    {
        $recipients = EmailConfigModel::getValue('recipients', '');
        return view('dlc.penerimaemail', compact('recipients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|string',
        ]);

        EmailConfigModel::setValue('recipients', $request->recipients);

        return redirect()->route('penerima-email')->with('success', 'Konfigurasi daftar email penerima berhasil disimpan.');
    }
}
