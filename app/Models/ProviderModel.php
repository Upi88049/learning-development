<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderModel extends Model
{
    protected $table = 'providers';
    protected $primaryKey = 'id_provider';

    protected $fillable = [
        'provider_code',
        'provider_name',
        'provider_type',
        'pic',
        'phone',
        'email',
        'address',
        'note',
    ];
}
