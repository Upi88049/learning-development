<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisiModel extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';

    protected $fillable = [
        'nama_divisi',
    ];

    public function departments()
    {
        return $this->hasMany(DepartmentModel::class, 'id_divisi', 'id_divisi');
    }
}
