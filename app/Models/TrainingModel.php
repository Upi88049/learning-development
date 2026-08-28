<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingModel extends Model
{
    protected $table = 'training';
    protected $primaryKey = 'id_training';
    public $timestamps = false;

    protected $fillable = [
        'jenis_training',
        'nama_training',
        'mandatory_training',
        'gol_training',
    ];

    public function staffTrainings()
    {
        return $this->hasMany(StaffTrainingModel::class, 'id_training', 'id_training');
    }
}
