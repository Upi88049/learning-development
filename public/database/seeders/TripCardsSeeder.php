<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripCardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trip_cards')->insert([
            [
                'name' => 'Jaka',
                'type' => 'Gold',
                'route' => 'Jakarta - Karawang',
                'trips' => 10,
                'price' => 500000,
                'validity_days' => 60,
            ],
            [
                'name' => 'Jaka',
                'type' => 'Silver',
                'route' => 'Jakarta - Karawang',
                'trips' => 6,
                'price' => 450000,
                'validity_days' => 30,
            ],
            [
                'name' => 'Kaban',
                'type' => 'Gold',
                'route' => 'Karawang - Bandung',
                'trips' => 10,
                'price' => 1000000,
                'validity_days' => 60,
            ],
            [
                'name' => 'Kaban',
                'type' => 'Silver',
                'route' => 'Karawang - Bandung',
                'trips' => 6,
                'price' => 750000,
                'validity_days' => 30,
            ],
            [
                'name' => 'Jaban',
                'type' => 'Gold',
                'route' => 'Jakarta - Bandung',
                'trips' => 10,
                'price' => 2000000,
                'validity_days' => 60,
            ],
            [
                'name' => 'Jaban',
                'type' => 'Silver',
                'route' => 'Jakarta - Bandung',
                'trips' => 6,
                'price' => 1350000,
                'validity_days' => 30,
            ],
        ]);
    }
}