<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DlcAccountModel extends Model
{
    protected $table = 'dlc_accounts';

    protected $fillable = [
        'username',
        'password',
        'nama',
    ];

    protected $hidden = [
        'password',
    ];
}
