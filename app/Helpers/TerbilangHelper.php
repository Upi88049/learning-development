<?php

namespace App\Helpers;

class TerbilangHelper
{
    private static $words = [
        '',
        'Satu',
        'Dua',
        'Tiga',
        'Empat',
        'Lima',
        'Enam',
        'Tujuh',
        'Delapan',
        'Sembilan',
        'Sepuluh',
        'Sebelas'
    ];

    /**
     * Convert integer/float to Indonesian words + "Rupiah"
     */
    public static function convert($number): string
    {
        $number = (float)$number;
        if ($number <= 0) {
            return 'Nol Rupiah';
        }

        $result = self::toWords($number);
        return trim(preg_replace('/\s+/', ' ', $result)) . ' Rupiah';
    }

    private static function toWords($number): string
    {
        $number = (float)$number;

        if ($number < 12) {
            return self::$words[(int)$number];
        } elseif ($number < 20) {
            return self::$words[(int)$number - 10] . ' Belas';
        } elseif ($number < 100) {
            return self::$words[(int)($number / 10)] . ' Puluh ' . self::toWords($number % 10);
        } elseif ($number < 200) {
            return 'Seratus ' . self::toWords($number - 100);
        } elseif ($number < 1000) {
            return self::$words[(int)($number / 100)] . ' Ratus ' . self::toWords($number % 100);
        } elseif ($number < 2000) {
            return 'Seribu ' . self::toWords($number - 1000);
        } elseif ($number < 1000000) {
            return self::toWords((int)($number / 1000)) . ' Ribu ' . self::toWords($number % 1000);
        } elseif ($number < 1000000000) {
            return self::toWords((int)($number / 1000000)) . ' Juta ' . self::toWords($number % 1000000);
        } elseif ($number < 1000000000000) {
            return self::toWords((int)($number / 1000000000)) . ' Miliar ' . self::toWords($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            return self::toWords((int)($number / 1000000000000)) . ' Triliun ' . self::toWords($number % 1000000000000);
        }

        return '';
    }
}
