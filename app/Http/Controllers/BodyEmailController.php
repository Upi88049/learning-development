<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailConfigModel;
use Illuminate\Support\Facades\Storage;

class BodyEmailController extends Controller
{
    public function index()
    {
        $rawRecipients = EmailConfigModel::getValue('recipients', '');
        $recipientsList = array_filter(array_map('trim', explode("\n", $rawRecipients)));
        $subject = EmailConfigModel::getValue('subject', 'Pemberitahuan Periode Training Need Analysis (TNA)');
        $defaultBody = "Yth. Immediate Manager,\n\nPeriode pengisian dan peninjauan Training Need Analysis (TNA) telah dibuka.\nSilakan akses dashboard Anda melalui link berikut untuk melihat daftar staff dan status training staff Anda:\n\nhttp://localhost/learningDevelopment/public/users\n\nTerima kasih,\nDharma Learning Center";
        $body = EmailConfigModel::getValue('body', $defaultBody);

        $attachmentPath = EmailConfigModel::getAttachmentPath();
        $attachmentName = EmailConfigModel::getAttachmentName();
        $attachmentSize = EmailConfigModel::getAttachmentSize();
        $hasAttachment = EmailConfigModel::hasAttachment();

        $formattedSize = '';
        if ($hasAttachment && $attachmentSize) {
            if ($attachmentSize >= 1048576) {
                $formattedSize = number_format($attachmentSize / 1048576, 2) . ' MB';
            } elseif ($attachmentSize >= 1024) {
                $formattedSize = number_format($attachmentSize / 1024, 2) . ' KB';
            } else {
                $formattedSize = $attachmentSize . ' bytes';
            }
        }

        return view('dlc.bodyemail', compact(
            'recipientsList',
            'subject',
            'body',
            'attachmentName',
            'attachmentPath',
            'formattedSize',
            'hasAttachment'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:20480',
        ], [
            'attachment.mimes' => 'Format file panduan harus berupa PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, atau ZIP.',
            'attachment.max' => 'Ukuran file panduan maksimal adalah 20 MB.',
        ]);

        EmailConfigModel::setValue('subject', $request->subject);
        EmailConfigModel::setValue('body', $request->body);

        if ($request->hasFile('attachment')) {
            $oldPath = EmailConfigModel::getAttachmentPath();
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $size = $file->getSize();

            $cleanName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . $cleanName . '.' . $extension;

            $path = $file->storeAs('guidance', $fileName, 'public');

            EmailConfigModel::setValue('attachment_path', $path);
            EmailConfigModel::setValue('attachment_name', $originalName);
            EmailConfigModel::setValue('attachment_size', $size);
        }

        return redirect()->route('body-email')->with('success', 'Konfigurasi Subjek, Body Email, dan Attachment berhasil disimpan.');
    }

    public function downloadAttachment()
    {
        $path = EmailConfigModel::getAttachmentPath();
        $name = EmailConfigModel::getAttachmentName() ?: 'Guidance_TNA.pdf';

        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, $name);
        }

        return redirect()->route('body-email')->with('error', 'File lampiran (guidance) tidak ditemukan di server.');
    }

    public function deleteAttachment()
    {
        $path = EmailConfigModel::getAttachmentPath();
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        EmailConfigModel::setValue('attachment_path', '');
        EmailConfigModel::setValue('attachment_name', '');
        EmailConfigModel::setValue('attachment_size', '');

        return redirect()->route('body-email')->with('success', 'File lampiran (guidance) berhasil dihapus.');
    }
}
