<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingModel extends Model
{
    protected $table = 'training';
    protected $primaryKey = 'id_training';
    public $timestamps = false;

    protected $fillable = [
        'kode_training',
        'jenis_training',
        'nama_training',
        'scope_training',
        'mandatory_training',
        'gol_training',
        'gambar',
        'deskripsi_training',
    ];

    /**
     * Get image URL if exists
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (!empty($this->gambar)) {
            if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
                return $this->gambar;
            }
            return asset('uploads/training/' . $this->gambar);
        }
        return null;
    }

    /**
     * Auto generate format kode training seperti TRN-001
     */
    public static function generateKodeTraining(): string
    {
        $maxId = self::max('id_training') ?? 0;
        return 'TRN-' . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);
    }

    public function staffTrainings()
    {
        return $this->hasMany(StaffTrainingModel::class, 'id_training', 'id_training');
    }
}
