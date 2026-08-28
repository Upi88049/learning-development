<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesLocationSeeder extends Seeder {
    public function run(): void {
        DB::table('sales_locations')->insert([
            ['name' => 'Stasiun Halim'],
            ['name' => 'Stasiun Karawang'],
            ['name' => 'Stasiun Padalarang'],
            ['name' => 'Stasiun Tegalluar Summarecon'],
        ]);
    }
}
