<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'id_department';

    protected $fillable = [
        'id_divisi',
        'nama_department',
    ];

    public function divisi()
    {
        return $this->belongsTo(DivisiModel::class, 'id_divisi', 'id_divisi');
    }

    public function staff()
    {
        return $this->hasMany(StaffModel::class, 'id_department', 'id_department');
    }
}
