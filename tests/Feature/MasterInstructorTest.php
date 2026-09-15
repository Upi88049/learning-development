<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StaffModel;
use App\Models\InstructorModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MasterInstructorTest extends TestCase
{
    use DatabaseTransactions;

    public function test_instructor_index_renders_with_table_columns_and_sidebar_link(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('instructor.index'));

        $response->assertStatus(200);

        // Sidebar link
        $response->assertSee('Master Instruktur');

        // Table headers
        $response->assertSee('No');
        $response->assertSee('Instructor Code');
        $response->assertSee('Instructor Name');
        $response->assertSee('Aksi');

        // Seeded instructors requested by user
        $response->assertSee('11230231');
        $response->assertSee('Marvel Moraro Alfonso Adijayato Nados');

        $response->assertSee('12061556');
        $response->assertSee('Firmansyah Heri Wibowo');

        $response->assertSee('13991008');
        $response->assertSee('Ahmad Saripudin');
    }

    public function test_instructor_create_page_renders(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('instructor.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Instruktur Baru');
        $response->assertSee('name="instructor_code"', false);
        $response->assertSee('name="instructor_name"', false);
    }

    public function test_instructor_store_validates_and_creates_instructor(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('instructor.store'), [
            'instructor_code' => '99887766',
            'instructor_name' => 'Budi Gunawan',
            'specialization' => 'Lean Manufacturing',
            'phone' => '081234567890',
            'email' => 'budi.gunawan@dharmap.com',
        ]);

        $response->assertRedirect(route('instructor.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('instructors', [
            'instructor_code' => '99887766',
            'instructor_name' => 'Budi Gunawan',
        ]);
    }

    public function test_instructor_store_rejects_duplicate_code(): void
    {
        $dlcStaff = StaffModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->post(route('instructor.store'), [
            'instructor_code' => '11230231', // Already exists
            'instructor_name' => 'Duplikat Marvel',
        ]);

        $response->assertSessionHasErrors(['instructor_code']);
    }

    public function test_instructor_edit_page_renders(): void
    {
        $dlcStaff = StaffModel::first();
        $instructor = InstructorModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->get(route('instructor.edit', $instructor->id_instructor));

        $response->assertStatus(200);
        $response->assertSee('Edit Instruktur');
        $response->assertSee($instructor->instructor_code);
        $response->assertSee($instructor->instructor_name);
    }

    public function test_instructor_update_modifies_instructor(): void
    {
        $dlcStaff = StaffModel::first();
        $instructor = InstructorModel::first();

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->put(route('instructor.update', $instructor->id_instructor), [
            'instructor_code' => $instructor->instructor_code,
            'instructor_name' => 'Nama Terupdate ' . time(),
            'specialization' => 'Updated Specialization',
        ]);

        $response->assertRedirect(route('instructor.index'));
        $response->assertSessionHas('success');
    }

    public function test_instructor_destroy_deletes_instructor(): void
    {
        $dlcStaff = StaffModel::first();

        $temp = InstructorModel::create([
            'instructor_code' => 'TEMP-INST-001',
            'instructor_name' => 'Instruktur Sementara',
        ]);

        $response = $this->withSession([
            'role' => 'DLC',
            'user' => $dlcStaff
        ])->delete(route('instructor.destroy', $temp->id_instructor));

        $response->assertRedirect(route('instructor.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('instructors', [
            'id_instructor' => $temp->id_instructor,
        ]);
    }
}
