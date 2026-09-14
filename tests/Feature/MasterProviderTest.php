<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;
use App\Models\ProviderModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MasterProviderTest extends TestCase
{
    use DatabaseTransactions;
    public function test_provider_index_renders_with_table_columns_and_sidebar_link(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('provider.index'));

        $response->assertStatus(200);

        // Check sidebar submenu
        $response->assertSee('Master Provider');

        // Check table column headers requested by user
        $response->assertSee('No');
        $response->assertSee('Provider Code');
        $response->assertSee('Provider Name');
        $response->assertSee('Provider Type');
        $response->assertSee('Person in Charge (PIC)');
        $response->assertSee('Phone');
        $response->assertSee('Email');
        $response->assertSee('Aksi');

        // Check seeded data
        $response->assertSee('PRV-001');
        $response->assertSee('PT Brainmatics Cipta Informatika');
        $response->assertSee('External');
        $response->assertSee('Romi Satria Wahono');
    }

    public function test_provider_create_page_renders(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('provider.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Provider Baru');
        $response->assertSee('name="provider_code"', false);
        $response->assertSee('name="provider_name"', false);
        $response->assertSee('name="provider_type"', false);
        $response->assertSee('name="pic"', false);
        $response->assertSee('name="phone"', false);
        $response->assertSee('name="email"', false);
    }

    public function test_provider_store_validates_and_creates_provider(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('provider.store'), [
            'provider_code' => 'PRV-TEST-999',
            'provider_name' => 'PT Mitra Training Solusindo',
            'provider_type' => 'External',
            'pic' => 'Ahmad Fauzi',
            'phone' => '081298765432',
            'email' => 'fauzi@mitratraining.com',
            'address' => 'Jl. Sudirman No. 45, Jakarta',
            'note' => 'Penyedia pelatihan keselamatan kerja K3',
        ]);

        $response->assertRedirect(route('provider.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('providers', [
            'provider_code' => 'PRV-TEST-999',
            'provider_name' => 'PT Mitra Training Solusindo',
            'pic' => 'Ahmad Fauzi',
        ]);
    }

    public function test_provider_store_rejects_duplicate_provider_code(): void
    {
        $dlcStaff = StaffModel::first();

        // Try creating with existing code PRV-001
        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('provider.store'), [
            'provider_code' => 'PRV-001',
            'provider_name' => 'Duplikat Provider',
            'provider_type' => 'External',
        ]);

        $response->assertSessionHasErrors(['provider_code']);
    }

    public function test_provider_edit_page_renders(): void
    {
        $dlcStaff = StaffModel::first();
        $provider = ProviderModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('provider.edit', $provider->id_provider));

        $response->assertStatus(200);
        $response->assertSee('Edit Provider');
        $response->assertSee($provider->provider_code);
        $response->assertSee($provider->provider_name);
    }

    public function test_provider_update_modifies_provider(): void
    {
        $dlcStaff = StaffModel::first();
        $provider = ProviderModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->put(route('provider.update', $provider->id_provider), [
            'provider_code' => $provider->provider_code,
            'provider_name' => $provider->provider_name . ' (Updated)',
            'provider_type' => 'Konsultan',
            'pic' => 'Updated PIC Name',
            'phone' => '081122334455',
            'email' => 'updated@provider.com',
            'address' => 'Updated Address',
            'note' => 'Updated Note',
        ]);

        $response->assertRedirect(route('provider.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('providers', [
            'id_provider' => $provider->id_provider,
            'pic' => 'Updated PIC Name',
            'provider_type' => 'Konsultan',
        ]);
    }

    public function test_provider_destroy_deletes_provider(): void
    {
        $dlcStaff = StaffModel::first();

        // Create a temporary provider to delete
        $temp = ProviderModel::create([
            'provider_code' => 'PRV-DEL-001',
            'provider_name' => 'Provider Akan Dihapus',
            'provider_type' => 'External',
        ]);

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->delete(route('provider.destroy', $temp->id_provider));

        $response->assertRedirect(route('provider.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('providers', [
            'id_provider' => $temp->id_provider,
        ]);
    }
}
