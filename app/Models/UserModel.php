<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'training';  
    protected $primaryKey = 'id_training';  

    protected $fillable = [
        'jenis_training',
        'nama_training',
        'mandatory_training',
        'gol_training',
    ];
}
