<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;
use App\Models\PenugasanTrainingModel;
use Illuminate\Support\Facades\Mail;

class CancelAndResendTest extends TestCase
{
    public function test_cancel_send_to_im_and_resend(): void
    {
        Mail::fake();

        $dlcStaff = StaffModel::first();
        $penugasan = PenugasanTrainingModel::first();

        // 1. Cancel send to IM
        $responseCancel = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post('/dlc/penugasan/' . $penugasan->id_penugasan . '/cancel-send-to-im');

        $responseCancel->assertRedirect(route('penugasan.index'));
        $penugasan->refresh();
        $this->assertFalse((bool)$penugasan->is_sent);
        $this->assertNull($penugasan->sent_at);

        // 2. Resend to IM
        $responseResend = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post('/dlc/penugasan/' . $penugasan->id_penugasan . '/send-to-im');

        $responseResend->assertRedirect(route('penugasan.index'));
        $penugasan->refresh();
        $this->assertTrue((bool)$penugasan->is_sent);
        $this->assertNotNull($penugasan->sent_at);
    }
}
