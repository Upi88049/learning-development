<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;
use App\Models\EmailConfigModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class BodyEmailAttachmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_body_email_page_renders_with_attachment_field(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('body-email'));

        $response->assertStatus(200);
        $response->assertSee('File Attachment Panduan (Guidance)');
        $response->assertSee('name="attachment"', false);
    }

    public function test_upload_attachment_saves_file_and_updates_config(): void
    {
        $dlcStaff = StaffModel::first();
        $file = UploadedFile::fake()->create('panduan_tna_2026.pdf', 150, 'application/pdf');

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('body-email.store'), [
            'subject' => 'Subjek Tes TNA',
            'body' => 'Isi body email tes',
            'attachment' => $file,
        ]);

        $response->assertRedirect(route('body-email'));
        $response->assertSessionHas('success');

        $this->assertEquals('panduan_tna_2026.pdf', EmailConfigModel::getAttachmentName());
        $savedPath = EmailConfigModel::getAttachmentPath();
        $this->assertNotEmpty($savedPath);
        Storage::disk('public')->assertExists($savedPath);
    }

    public function test_download_attachment(): void
    {
        $dlcStaff = StaffModel::first();
        $file = UploadedFile::fake()->create('panduan_download.pdf', 100, 'application/pdf');

        // Store file first
        $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('body-email.store'), [
            'subject' => 'Subjek Tes',
            'body' => 'Body tes',
            'attachment' => $file,
        ]);

        $downloadResponse = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('body-email.download-attachment'));

        $downloadResponse->assertStatus(200);
        $downloadResponse->assertDownload('panduan_download.pdf');
    }

    public function test_delete_attachment_removes_file_and_clears_config(): void
    {
        $dlcStaff = StaffModel::first();
        $file = UploadedFile::fake()->create('panduan_hapus.pdf', 100, 'application/pdf');

        $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('body-email.store'), [
            'subject' => 'Subjek Tes',
            'body' => 'Body tes',
            'attachment' => $file,
        ]);

        $savedPath = EmailConfigModel::getAttachmentPath();
        Storage::disk('public')->assertExists($savedPath);

        $deleteResponse = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->delete(route('body-email.delete-attachment'));

        $deleteResponse->assertRedirect(route('body-email'));
        $deleteResponse->assertSessionHas('success');

        Storage::disk('public')->assertMissing($savedPath);
        $this->assertEmpty(EmailConfigModel::getAttachmentPath());
        $this->assertEmpty(EmailConfigModel::getAttachmentName());
    }

    public function test_send_email_executes_successfully_with_attachment(): void
    {
        Mail::fake();

        $dlcStaff = StaffModel::first();
        $file = UploadedFile::fake()->create('panduan_kirim_email.pdf', 100, 'application/pdf');

        // Configure recipients & attachment
        EmailConfigModel::setValue('recipients', "manager.test@dharmap.com");
        EmailConfigModel::setValue('subject', "Pengumuman TNA dengan Panduan");
        EmailConfigModel::setValue('body', "Silakan cek file lampiran.");

        $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('body-email.store'), [
            'subject' => 'Pengumuman TNA dengan Panduan',
            'body' => 'Silakan cek file lampiran.',
            'attachment' => $file,
        ]);

        $sendResponse = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('periode-tna.sendEmail'));

        $sendResponse->assertSessionHas('success');
    }
}
