<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
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
}
