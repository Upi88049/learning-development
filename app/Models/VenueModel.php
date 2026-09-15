<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueModel extends Model
{
    protected $table = 'venues';
    protected $primaryKey = 'id_venue';

    protected $fillable = [
        'venue_code',
        'venue_name',
        'venue_type',
    ];
}
