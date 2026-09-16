<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CoeEvent extends Model
{
    protected $table = 'coe_events';
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'no_event',
        'id_training',
        'nama_training',
        'batch_training',
        'tanggal_per_batch',
        'tanggal_selesai_batch',
        'jumlah_peserta_tna',
        'jumlah_peserta_non_tna',
        'biaya_investasi_perorang',
        'total_biaya',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_per_batch' => 'date:Y-m-d',
        'tanggal_selesai_batch' => 'date:Y-m-d',
        'jumlah_peserta_tna' => 'integer',
        'jumlah_peserta_non_tna' => 'integer',
        'biaya_investasi_perorang' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    /**
     * Cek apakah event berlangsung lebih dari 1 hari (multi-day)
     */
    public function getIsMultiDayAttribute(): bool
    {
        return $this->tanggal_selesai_batch && $this->tanggal_selesai_batch->gt($this->tanggal_per_batch);
    }

    /**
     * Format rentang tanggal pelaksanaan
     */
    public function getDateRangeFormattedAttribute(): string
    {
        if ($this->is_multi_day) {
            return $this->tanggal_per_batch->translatedFormat('d M Y') . ' s/d ' . $this->tanggal_selesai_batch->translatedFormat('d M Y');
        }
        return $this->tanggal_per_batch->translatedFormat('d F Y');
    }

    /**
     * Total Peserta = Peserta TNA + Peserta Non-TNA
     */
    public function getTotalPesertaAttribute(): int
    {
        return ($this->jumlah_peserta_tna ?? 0) + ($this->jumlah_peserta_non_tna ?? 0);
    }

    /**
     * Format Biaya Investasi Perorang ke Rupiah
     */
    public function getFormattedBiayaAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya_investasi_perorang ?? 0, 0, ',', '.');
    }

    /**
     * Format Total Biaya ke Rupiah
     */
    public function getFormattedTotalBiayaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya ?? 0, 0, ',', '.');
    }

    /**
     * Relasi ke Master Training
     */
    public function training()
    {
        return $this->belongsTo(TrainingModel::class, 'id_training', 'id_training');
    }

    /**
     * Relasi ke Data Detail Training Event
     */
    public function trainingEvent()
    {
        return $this->hasOne(CoeTrainingEvent::class, 'id_event', 'id_event');
    }

    /**
     * Auto generate format No Event seperti COE-2026-001
     */
    public static function generateNoEvent(): string
    {
        $year = Carbon::now()->format('Y');
        $prefix = "COE-{$year}-";
        $lastEvent = self::where('no_event', 'LIKE', "{$prefix}%")
            ->orderBy('id_event', 'desc')
            ->first();

        if ($lastEvent) {
            $lastNum = (int) substr($lastEvent->no_event, strlen($prefix));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}
