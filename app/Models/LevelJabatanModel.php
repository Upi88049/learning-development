<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelJabatanModel extends Model
{
    protected $table = 'level_jabatan';
    protected $primaryKey = 'id_level_jabatan';

    protected $fillable = [
        'kode_level_jabatan',
        'keterangan',
    ];

    public function staff()
    {
        return $this->hasMany(StaffModel::class, 'id_jabatan_staff', 'id_level_jabatan');
    }
}
