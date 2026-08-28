<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailConfigModel;

class BodyEmailController extends Controller
{
    public function index()
    {
        $rawRecipients = EmailConfigModel::getValue('recipients', '');
        $recipientsList = array_filter(array_map('trim', explode("\n", $rawRecipients)));
        $subject = EmailConfigModel::getValue('subject', 'Pemberitahuan Periode Training Need Analysis (TNA)');
        $defaultBody = "Yth. Immediate Manager,\n\nPeriode pengisian dan peninjauan Training Need Analysis (TNA) telah dibuka.\nSilakan akses dashboard Anda melalui link berikut untuk melihat daftar staff dan status training staff Anda:\n\nhttp://localhost/learningDevelopment/public/users\n\nTerima kasih,\nLearning & Development DLC";
        $body = EmailConfigModel::getValue('body', $defaultBody);

        return view('dlc.bodyemail', compact('recipientsList', 'subject', 'body'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        EmailConfigModel::setValue('subject', $request->subject);
        EmailConfigModel::setValue('body', $request->body);

        return redirect()->route('body-email')->with('success', 'Konfigurasi Subjek & Body Email berhasil disimpan.');
    }
}
