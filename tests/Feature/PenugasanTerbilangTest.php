<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;

class PenugasanTerbilangTest extends TestCase
{
    public function test_penugasan_action_terbilang_endpoint_returns_converted_words(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->postJson('/dlc/penugasan?action=terbilang', [
            'amount' => 1500000
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'terbilang' => 'Satu Juta Lima Ratus Ribu Rupiah'
        ]);
    }

    public function test_penugasan_create_page_renders_with_realtime_terbilang_script(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get('/dlc/penugasan/create?from_request=8');

        $response->assertStatus(200);
        $response->assertSee('toTerbilang');
        $response->assertSee('inputTerbilang.value = toTerbilang(total)', false);
        $response->assertSee('id="terbilang"', false);
    }
}
