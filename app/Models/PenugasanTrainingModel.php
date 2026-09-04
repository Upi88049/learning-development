<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanTrainingModel extends Model
{
    use HasFactory;

    protected $table = 'penugasan_training';
    protected $primaryKey = 'id_penugasan';

    protected $fillable = [
        'no_form',
        'id_request_outhouse',
        'nama_training',
        'jenis_training',
        'sub_co',
        'divisi',
        'peserta_json',
        'jumlah_peserta',
        'biaya_per_peserta',
        'total_biaya',
        'terbilang',
        'alasan_pelatihan',
        'nama_atasan',
        'divisi_atasan',
        'jabatan_atasan',
        'tempat_tanggal_training',
        'tempat_tanggal_persetujuan',
        'nama_direktur',
        'jabatan_direktur',
        'nama_im',
        'bagian_im',
        'penyetujui_nama',
        'penyetujui_jabatan',
        'konfirmasi_nama',
        'konfirmasi_jabatan',
        'is_sent',
        'sent_at',
    ];

    protected $casts = [
        'biaya_per_peserta' => 'decimal:2',
        'total_biaya' => 'decimal:2',
        'jumlah_peserta' => 'integer',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * Decode peserta_json into PHP array
     */
    public function getPesertaAttribute(): array
    {
        if (empty($this->peserta_json)) {
            return [];
        }
        $decoded = json_decode($this->peserta_json, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Relationship with RequestOuthouse
     */
    public function requestOuthouse()
    {
        return $this->belongsTo(RequestOuthouseModel::class, 'id_request_outhouse', 'id_request_outhouse');
    }
}
