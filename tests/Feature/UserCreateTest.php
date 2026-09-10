<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;

class UserCreateTest extends TestCase
{
    public function test_user_create_page_renders_with_searchable_select_for_dlc(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get('/users/create');

        $response->assertStatus(200);
        $response->assertSee('select-searchable');
        $response->assertSee('id_immediate_manager');
        $response->assertSee('data-placeholder="-- Pilih / Cari Immediate Manager --"', false);
    }

    public function test_staff_edit_page_renders_with_searchable_select_for_dlc(): void
    {
        $staff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $staff
        ])->get('/staff/edit/' . $staff->id_staff);

        $response->assertStatus(200);
        $response->assertSee('select-searchable');
        $response->assertSee('id_immediate_manager');
        $response->assertSee('data-placeholder="-- Pilih / Cari Immediate Manager --"', false);
    }

    public function test_user_store_with_immediate_manager(): void
    {
        $dlcStaff = StaffModel::first();
        $testNpk = 987650;
        StaffModel::where('npk_staff', $testNpk)->delete();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post('/users/store', [
            'npk_staff' => $testNpk,
            'nama_staff' => 'Testing Searchable Select Staff',
            'tanggal_lahir' => '1995-05-15',
            'id_jabatan_staff' => 1,
            'id_immediate_manager' => $dlcStaff->id_staff,
        ]);

        $response->assertRedirect(route('member-list'));
        $created = StaffModel::where('npk_staff', $testNpk)->first();
        $this->assertNotNull($created);
        $this->assertEquals($dlcStaff->id_staff, $created->id_immediate_manager);

        // Cleanup
        $created->delete();
    }

    public function test_user_store_without_immediate_manager(): void
    {
        $dlcStaff = StaffModel::first();
        $testNpk = 987651;
        StaffModel::where('npk_staff', $testNpk)->delete();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post('/users/store', [
            'npk_staff' => $testNpk,
            'nama_staff' => 'Testing Searchable Select Staff No Mgr',
            'tanggal_lahir' => '1995-05-15',
            'id_jabatan_staff' => 1,
            'id_immediate_manager' => '',
        ]);

        $response->assertRedirect(route('member-list'));
        $created = StaffModel::where('npk_staff', $testNpk)->first();
        $this->assertNotNull($created);
        $this->assertNull($created->id_immediate_manager);

        // Cleanup
        $created->delete();
    }
}
