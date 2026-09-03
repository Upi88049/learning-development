<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestOuthouseModel extends Model
{
    use HasFactory;

    protected $table = 'request_outhouse';
    protected $primaryKey = 'id_request_outhouse';

    protected $fillable = [
        'no_request',
        'id_staff',
        'id_immediate_manager',
        'judul_training',
        'deskripsi_training',
        'reason',
        'status',
        'alasan_reject',
    ];

    /**
     * Generate unique No. Request in format REQ-OH-YYYYMMDD-XXXX
     */
    public static function generateNoRequest(): string
    {
        $prefix = 'REQ-OH-' . date('Ymd') . '-';
        $latest = self::where('no_request', 'LIKE', $prefix . '%')
            ->orderBy('id_request_outhouse', 'desc')
            ->first();

        if ($latest) {
            $parts = explode('-', $latest->no_request);
            $lastNumber = intval(end($parts));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Staff who receives the training
     */
    public function staff()
    {
        return $this->belongsTo(StaffModel::class, 'id_staff', 'id_staff');
    }

    /**
     * Immediate Manager who submitted the request
     */
    public function immediateManager()
    {
        return $this->belongsTo(StaffModel::class, 'id_immediate_manager', 'id_staff');
    }
}
