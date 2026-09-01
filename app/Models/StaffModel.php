<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffModel extends Model
{
    protected $table = 'staff';  
    protected $primaryKey = 'id_staff';  

    protected $fillable = [
        'npk_staff',
        'nama_staff',
        'tanggal_lahir',
        'id_divisi',
        'id_department',
        'id_jabatan_staff',
        'id_immediate_manager',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }
        return \Carbon\Carbon::parse($this->tanggal_lahir)->age . ' Tahun';
    }

    public function divisi()
    {
        return $this->belongsTo(DivisiModel::class, 'id_divisi', 'id_divisi');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'id_department', 'id_department');
    }

    public function levelJabatan()
    {
        return $this->belongsTo(LevelJabatanModel::class, 'id_jabatan_staff', 'id_level_jabatan');
    }

    public function immediateManager()
    {
        return $this->belongsTo(StaffModel::class, 'id_immediate_manager', 'id_staff');
    }

    public function subordinates()
    {
        return $this->hasMany(StaffModel::class, 'id_immediate_manager', 'id_staff');
    }

    public function isDlc()
    {
        // Department 3 is Learning & Development (DLC)
        if ($this->id_department == 3) {
            return true;
        }
        if ($this->relationLoaded('department') && $this->department) {
            return str_contains(strtolower($this->department->nama_department), 'learning') || str_contains(strtolower($this->department->nama_department), 'dlc');
        }
        return false;
    }

    public function isImmediateManager()
    {
        // Staff is set as immediate manager for any staff
        if (StaffModel::where('id_immediate_manager', $this->id_staff)->exists()) {
            return true;
        }
        // Or holds a managerial level jabatan (not SF / Staff id 4)
        if ($this->id_jabatan_staff != 4) {
            return true;
        }
        return false;
    }
}
