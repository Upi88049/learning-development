<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserModel;
use App\Models\StaffModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class TrainingDetailAndImageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $uploadDir = public_path('uploads/training');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }
    }

    public function test_training_create_page_renders_image_and_description_inputs(): void
    {
        $dlcStaff = StaffModel::first();
        $response = $this->withSession(['role' => 'DLC', 'user' => $dlcStaff])
            ->get(route('training.create'));

        $response->assertStatus(200);
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('name="gambar"', false);
        $response->assertSee('name="deskripsi_training"', false);
        $response->assertSee('imagePreview', false);
    }

    public function test_training_store_saves_image_and_description(): void
    {
        $dlcStaff = StaffModel::first();
        $uniqueCode = 'TEST-IMG-' . rand(1000, 9999);
        $fakeImage = UploadedFile::fake()->image('test_brochure.jpg', 600, 400);

        $response = $this->withSession(['role' => 'DLC', 'user' => $dlcStaff])
            ->post(route('training.store'), [
                'kode_training' => $uniqueCode,
                'nama_training' => 'Advanced AI & Machine Learning',
                'scope_training' => 'In House',
                'jenis_training' => 'Technical',
                'mandatory_training' => 'Mandatory',
                'gol_training' => 'III',
                'deskripsi_training' => "Silabus:\n1. Introduction to Machine Learning\n2. Deep Learning with PyTorch",
                'gambar' => $fakeImage,
            ]);

        $response->assertRedirect(route('training.index'));

        $training = UserModel::where('kode_training', $uniqueCode)->first();
        $this->assertNotNull($training);
        $this->assertNotNull($training->gambar);
        $this->assertEquals("Silabus:\n1. Introduction to Machine Learning\n2. Deep Learning with PyTorch", $training->deskripsi_training);
        $this->assertTrue(File::exists(public_path('uploads/training/' . $training->gambar)));

        // Clean up uploaded file and record
        if ($training->gambar && File::exists(public_path('uploads/training/' . $training->gambar))) {
            File::delete(public_path('uploads/training/' . $training->gambar));
        }
        $training->delete();
    }

    public function test_training_edit_and_update_with_image_and_description(): void
    {
        $dlcStaff = StaffModel::first();
        $uniqueCode = 'TEST-EDT-' . rand(1000, 9999);
        $fakeImage = UploadedFile::fake()->image('initial.jpg', 400, 400);

        $training = UserModel::create([
            'kode_training' => $uniqueCode,
            'nama_training' => 'Data Engineering 101',
            'scope_training' => 'In House',
            'jenis_training' => 'Technical',
            'mandatory_training' => 'Mandatory',
            'gol_training' => 'III',
            'deskripsi_training' => 'Initial deskripsi',
            'gambar' => null,
        ]);

        $responseEdit = $this->withSession(['role' => 'DLC', 'user' => $dlcStaff])
            ->get(route('training.edit', $training->id_training));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('enctype="multipart/form-data"', false);
        $responseEdit->assertSee('name="deskripsi_training"', false);

        // Update with new description & image
        $responseUpdate = $this->withSession(['role' => 'DLC', 'user' => $dlcStaff])
            ->put(route('training.update', $training->id_training), [
                'kode_training' => $uniqueCode,
                'nama_training' => 'Data Engineering 101 Updated',
                'scope_training' => 'In House',
                'jenis_training' => 'Technical',
                'deskripsi_training' => 'Updated syllabus content',
                'gambar' => $fakeImage,
            ]);

        $responseUpdate->assertRedirect(route('training.index'));

        $training->refresh();
        $this->assertEquals('Updated syllabus content', $training->deskripsi_training);
        $this->assertNotNull($training->gambar);
        $uploadedPath = public_path('uploads/training/' . $training->gambar);
        $this->assertTrue(File::exists($uploadedPath));

        // Test remove image via hapus_gambar checkbox
        $responseRemoveImg = $this->withSession(['role' => 'DLC', 'user' => $dlcStaff])
            ->put(route('training.update', $training->id_training), [
                'kode_training' => $uniqueCode,
                'nama_training' => 'Data Engineering 101 Updated',
                'scope_training' => 'In House',
                'jenis_training' => 'Technical',
                'deskripsi_training' => 'Updated syllabus content',
                'hapus_gambar' => '1',
            ]);

        $training->refresh();
        $this->assertNull($training->gambar);
        $this->assertFalse(File::exists($uploadedPath));

        $training->delete();
    }

    public function test_training_index_displays_image_and_modal(): void
    {
        $dlcStaff = StaffModel::first();
        $uniqueCode = 'TEST-IDX-' . rand(1000, 9999);
        $training = UserModel::create([
            'kode_training' => $uniqueCode,
            'nama_training' => 'Leadership Excellence Program',
            'scope_training' => 'In House',
            'jenis_training' => 'Soft Skill',
            'mandatory_training' => 'Mandatory',
            'gol_training' => 'IV',
            'deskripsi_training' => 'Pelatihan kepemimpinan strategis.',
            'gambar' => null,
        ]);

        $response = $this->withSession(['role' => 'DLC', 'user' => $dlcStaff])
            ->get(route('training.index'));

        $response->assertStatus(200);
        $response->assertSee('modalTrainingDetail', false);
        $response->assertSee($uniqueCode);
        $response->assertSee('Leadership Excellence Program');
        $response->assertSee('btn-view-detail', false);

        $training->delete();
    }

    public function test_staff_detail_dlc_renders_training_card_trigger_and_modal(): void
    {
        $staff = StaffModel::first();
        $this->assertNotNull($staff, 'Staff record is required for this test');

        $response = $this->withSession(['role' => 'DLC', 'user' => $staff])
            ->get(route('staff.detail', $staff->id_staff));

        $response->assertStatus(200);
        $response->assertSee('modalTrainingDetail', false);
        $response->assertSee('training-card-clickable', false);
        $response->assertSee('data-deskripsi', false);
    }

    public function test_user_detail_im_renders_training_card_trigger_and_modal(): void
    {
        $staff = StaffModel::first();
        $this->assertNotNull($staff, 'Staff record is required for this test');

        $response = $this->withSession([
            'role' => 'Immediate Manager',
            'user' => $staff
        ])->get(route('users.detail', $staff->id_staff));

        $response->assertStatus(200);
        $response->assertSee('modalTrainingDetail', false);
        $response->assertSee('training-card-clickable', false);
        $response->assertSee('data-deskripsi', false);
    }
}
