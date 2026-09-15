<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;
use App\Models\VenueModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MasterVenueTest extends TestCase
{
    use DatabaseTransactions;

    public function test_venue_index_renders_with_table_columns_and_sidebar_link(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('venue.index'));

        $response->assertStatus(200);

        // Sidebar link
        $response->assertSee('Master Venue');

        // Table headers
        $response->assertSee('No');
        $response->assertSee('Venue Code');
        $response->assertSee('Venue Name');
        $response->assertSee('Venue Type');
        $response->assertSee('Aksi');

        // Seeded venues requested by user
        $response->assertSee('RT-001');
        $response->assertSee('Ruang Training');
        $response->assertSee('Internal');

        $response->assertSee('RT-002');
        $response->assertSee('DOJO');

        $response->assertSee('RT-003');
        $response->assertSee('Auditorium');

        $response->assertSee('RT-004');
        $response->assertSee('Rojo Safety');
        $response->assertSee('External');
    }

    public function test_venue_create_page_renders(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('venue.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Venue Baru');
        $response->assertSee('name="venue_code"', false);
        $response->assertSee('name="venue_name"', false);
        $response->assertSee('name="venue_type"', false);
    }

    public function test_venue_store_validates_and_creates_venue(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('venue.store'), [
            'venue_code' => 'RT-999',
            'venue_name' => 'Ruang Workshop Inovasi',
            'venue_type' => 'Internal',
        ]);

        $response->assertRedirect(route('venue.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('venues', [
            'venue_code' => 'RT-999',
            'venue_name' => 'Ruang Workshop Inovasi',
            'venue_type' => 'Internal',
        ]);
    }

    public function test_venue_store_rejects_duplicate_code(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('venue.store'), [
            'venue_code' => 'RT-001', // Already exists
            'venue_name' => 'Duplikat Ruang Training',
            'venue_type' => 'Internal',
        ]);

        $response->assertSessionHasErrors(['venue_code']);
    }

    public function test_venue_edit_page_renders(): void
    {
        $dlcStaff = StaffModel::first();
        $venue = VenueModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('venue.edit', $venue->id_venue));

        $response->assertStatus(200);
        $response->assertSee('Edit Venue');
        $response->assertSee($venue->venue_code);
        $response->assertSee($venue->venue_name);
    }

    public function test_venue_update_modifies_venue(): void
    {
        $dlcStaff = StaffModel::first();
        $venue = VenueModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->put(route('venue.update', $venue->id_venue), [
            'venue_code' => $venue->venue_code,
            'venue_name' => 'Nama Venue Diupdate ' . time(),
            'venue_type' => 'Internal',
        ]);

        $response->assertRedirect(route('venue.index'));
        $response->assertSessionHas('success');
    }

    public function test_venue_destroy_deletes_venue(): void
    {
        $dlcStaff = StaffModel::first();

        $temp = VenueModel::create([
            'venue_code' => 'RT-TEMP-001',
            'venue_name' => 'Venue Sementara',
            'venue_type' => 'Internal',
        ]);

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->delete(route('venue.destroy', $temp->id_venue));

        $response->assertRedirect(route('venue.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('venues', [
            'id_venue' => $temp->id_venue,
        ]);
    }
}
