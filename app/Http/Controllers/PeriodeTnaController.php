<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\EmailConfigModel;
use Carbon\Carbon;

class PeriodeTnaController extends Controller
{
    public function index()
    {
        $rawRecipients = EmailConfigModel::getValue('recipients', '');
        $recipientsCount = count(array_filter(array_map('trim', explode("\n", $rawRecipients))));

        $tnaStartDate = EmailConfigModel::getTnaStartDate();
        $tnaEndDate = EmailConfigModel::getTnaEndDate();
        $isTnaActive = EmailConfigModel::isTnaActive();

        return view('dlc.periodetna', compact('recipientsCount', 'tnaStartDate', 'tnaEndDate', 'isTnaActive'));
    }

    public function savePeriod(Request $request)
    {
        $request->validate([
            'tna_start_date' => 'required|date',
            'tna_end_date' => 'required|date|after_or_equal:tna_start_date',
        ], [
            'tna_start_date.required' => 'Tanggal Start harus diisi.',
            'tna_end_date.required' => 'Tanggal End harus diisi.',
            'tna_end_date.after_or_equal' => 'Tanggal End harus sama atau setelah Tanggal Start.',
        ]);

        EmailConfigModel::setValue('tna_start_date', $request->tna_start_date);
        EmailConfigModel::setValue('tna_end_date', $request->tna_end_date);

        return redirect()->route('periode-tna')->with('success', 'Periode TNA berhasil disimpan dan diperbarui.');
    }

    public function closeTna()
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        EmailConfigModel::setValue('tna_end_date', $yesterday);

        return redirect()->route('periode-tna')->with('success', 'Periode TNA berhasil ditutup.');
    }

    public function sendEmail(Request $request)
    {
        $rawRecipients = EmailConfigModel::getValue('recipients', '');
        $recipients = array_filter(array_map('trim', explode("\n", $rawRecipients)));

        if (empty($recipients)) {
            return redirect()->route('periode-tna')->with('error', 'Gagal mengirim email: Belum ada alamat email penerima yang dikonfigurasi!');
        }

        $subject = EmailConfigModel::getValue('subject', 'Pemberitahuan Periode Training Need Analysis (TNA)');
        $body = EmailConfigModel::getValue('body', "Yth. Immediate Manager,\n\nPeriode TNA telah dibuka. Akses link: http://localhost/learningDevelopment/public/users");

        $successCount = 0;
        $failedEmails = [];
        $lastErrorMessage = '';

        foreach ($recipients as $toEmail) {
            try {
                Mail::raw($body, function ($message) use ($toEmail, $subject) {
                    $message->to($toEmail)->subject($subject);
                });
                $successCount++;
                Log::info('Email notifikasi TNA berhasil dikirim ke: ' . $toEmail);
            } catch (\Exception $e) {
                $lastErrorMessage = $e->getMessage();
                $failedEmails[] = $toEmail;
                Log::warning('Email sending failed for ' . $toEmail . ': ' . $e->getMessage());
            }
        }

        if ($successCount === 0 && !empty($failedEmails)) {
            $hint = str_contains($lastErrorMessage, '535') || str_contains($lastErrorMessage, 'BadCredentials')
                ? 'Autentikasi SMTP Gmail ditolak (Password salah/bukan App Password 16 karakter).'
                : 'Koneksi SMTP gagal: ' . \Illuminate\Support\Str::limit($lastErrorMessage, 120);
            return redirect()->route('periode-tna')->with('error', "Gagal mengirim email: Seluruh penerima (" . count($failedEmails) . " alamat) tidak dapat dikirim. {$hint}");
        }

        if (!empty($failedEmails)) {
            return redirect()->route('periode-tna')->with('warning', "Email berhasil dikirim ke {$successCount} alamat, namun gagal ke " . count($failedEmails) . " alamat (" . implode(', ', $failedEmails) . ").");
        }

        return redirect()->route('periode-tna')->with('success', 'Email notifikasi TNA berhasil dikirim ke ' . $successCount . ' alamat penerima. Anda juga dapat memeriksa folder "Terkirim" di akun Gmail.');
    }
}
