<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTrainingModel extends Model
{
    protected $table = 'staff_training';
    protected $primaryKey = 'id_staff_training';
    public $timestamps = false;

    protected $fillable = [
        'id_staff',
        'id_training',
        'id_status',
    ];

    public function staff()
    {
        return $this->belongsTo(StaffModel::class, 'id_staff', 'id_staff');
    }

    public function training()
    {
        return $this->belongsTo(UserModel::class, 'id_training', 'id_training');
    }
}