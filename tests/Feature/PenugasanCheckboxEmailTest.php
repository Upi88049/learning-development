<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;
use App\Models\PenugasanTrainingModel;
use Illuminate\Support\Facades\Mail;

class PenugasanCheckboxEmailTest extends TestCase
{
    public function test_store_with_is_sent_checkbox_sends_email_to_im(): void
    {
        Mail::fake();

        $dlcStaff = StaffModel::first();
        $imStaff = StaffModel::whereNotNull('email')->where('email', '!=', '')->first();

        $payload = [
            'nama_training' => 'Test Checkbox Email Store',
            'jenis_training' => 'Out House Training',
            'sub_co' => 'PT. Dharma Polimetal Tbk',
            'divisi' => 'HRGA',
            'peserta' => [
                [
                    'npk' => '99122022',
                    'nama' => 'Peserta Test',
                    'bagian' => 'HRMS',
                    'jabatan' => 'Staff',
                    'atasan' => $imStaff->nama_staff,
                ]
            ],
            'biaya_per_peserta' => '500000',
            'nama_im' => $imStaff->nama_staff,
            'bagian_im' => 'DH HRMS',
            'is_sent' => '1', // Checkbox checked, but NO action_save_send!
        ];

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff,
        ])->post(route('penugasan.store'), $payload);

        $response->assertRedirect(route('penugasan.index'));
        $response->assertSessionHas('success');

        $created = PenugasanTrainingModel::where('nama_training', 'Test Checkbox Email Store')->first();
        $this->assertNotNull($created);
        $this->assertTrue((bool)$created->is_sent);
        $this->assertNotNull($created->sent_at);
    }

    public function test_update_with_is_sent_checkbox_sends_email_to_im(): void
    {
        Mail::fake();

        $dlcStaff = StaffModel::first();
        $imStaff = StaffModel::whereNotNull('email')->where('email', '!=', '')->first();

        $penugasan = PenugasanTrainingModel::first();
        // Set unsent first
        $penugasan->update([
            'is_sent' => false,
            'sent_at' => null,
            'nama_im' => $imStaff->nama_staff,
        ]);

        $payload = [
            'nama_training' => $penugasan->nama_training,
            'jenis_training' => $penugasan->jenis_training,
            'sub_co' => $penugasan->sub_co,
            'divisi' => $penugasan->divisi,
            'peserta' => [
                [
                    'npk' => '99122022',
                    'nama' => 'Peserta Update',
                    'bagian' => 'HRMS',
                    'jabatan' => 'Staff',
                    'atasan' => $imStaff->nama_staff,
                ]
            ],
            'biaya_per_peserta' => '600000',
            'nama_im' => $imStaff->nama_staff,
            'bagian_im' => 'DH HRMS',
            'is_sent_submitted' => '1',
            'is_sent' => '1', // Checkbox checked, NO action_save_send
        ];

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff,
        ])->put(route('penugasan.update', $penugasan->id_penugasan), $payload);

        $response->assertRedirect(route('penugasan.index'));
        $response->assertSessionHas('success');

        $penugasan->refresh();
        $this->assertTrue((bool)$penugasan->is_sent);
        $this->assertNotNull($penugasan->sent_at);
    }
}
