<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailConfigModel extends Model
{
    protected $table = 'email_configs';
    protected $fillable = ['key', 'value'];

    public static function getValue($key, $default = '')
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    public static function setValue($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getTnaStartDate()
    {
        return static::getValue('tna_start_date', '');
    }

    public static function getTnaEndDate()
    {
        return static::getValue('tna_end_date', '');
    }

    public static function isTnaActive(): bool
    {
        $start = static::getTnaStartDate();
        $end = static::getTnaEndDate();

        if (empty($start) || empty($end)) {
            return false;
        }

        $today = Carbon::today()->format('Y-m-d');
        return ($today >= $start && $today <= $end);
    }
}
