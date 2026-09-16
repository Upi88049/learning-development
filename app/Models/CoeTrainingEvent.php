<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoeTrainingEvent extends Model
{
    protected $table = 'coe_training_events';
    protected $primaryKey = 'id_training_event';

    protected $fillable = [
        'id_event',
        'tipe_penyelenggara',
        'nama_penyelenggara',
        'trainer',
        'manager_class',
        'tipe_evaluasi',
        'tipe_soal',
        'ruangan',
        'peserta_tna',
        'peserta_non_tna',
        'catatan',
    ];

    protected $casts = [
        'peserta_tna' => 'array',
        'peserta_non_tna' => 'array',
    ];

    /**
     * Relasi ke Event Kalender
     */
    public function coeEvent()
    {
        return $this->belongsTo(CoeEvent::class, 'id_event', 'id_event');
    }

    /**
     * Hitung total peserta TNA dari array
     */
    public function getTotalPesertaTnaAttribute(): int
    {
        return is_array($this->peserta_tna) ? count($this->peserta_tna) : 0;
    }

    /**
     * Hitung total peserta Non-TNA dari array
     */
    public function getTotalPesertaNonTnaAttribute(): int
    {
        return is_array($this->peserta_non_tna) ? count($this->peserta_non_tna) : 0;
    }

    /**
     * Hitung total seluruh peserta
     */
    public function getTotalPesertaAttribute(): int
    {
        return $this->total_peserta_tna + $this->total_peserta_non_tna;
    }
}
