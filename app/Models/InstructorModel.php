<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorModel extends Model
{
    protected $table = 'instructors';
    protected $primaryKey = 'id_instructor';

    protected $fillable = [
        'instructor_code',
        'instructor_name',
        'email',
        'phone',
        'specialization',
    ];
}
