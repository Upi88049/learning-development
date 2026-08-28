<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorSeeder extends Seeder {
    public function run(): void {
        // Ambil ID lokasi berdasarkan nama
        $locations = DB::table('sales_locations')->pluck('id', 'name');

        // Data operator lengkap
        $operators = [
            ['name' => 'Marcelino Kondoy', 'nipp' => '499128', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Marriola Steven', 'nipp' => '499096', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Alliyah Saffanah Anggraeni Tjahjadi', 'nipp' => '499089', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Aridhita Eltia Anggraeni', 'nipp' => '499126', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Deby Wildan Ramdani', 'nipp' => '499127', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Desti Alia Rahma', 'nipp' => '499108', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Desvania Tri Crisna', 'nipp' => '499109', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Marlina Sari', 'nipp' => '499129', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Siska Utari Agustina', 'nipp' => '499215', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Siti Lia Khofipah', 'nipp' => '499118', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Yori Dela Lovenia', 'nipp' => '499120', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Aldo Alfansah', 'nipp' => '499193', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Axel Dhiaulhaq', 'nipp' => '499152', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Fajri Hidayat', 'nipp' => '499110', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Muhammad Haikal', 'nipp' => '2401645', 'sales_location' => 'Stasiun Halim'],
            ['name' => 'Royan Muhammad Firdaus', 'nipp' => '2401652', 'sales_location' => 'Stasiun Halim'],

            ['name' => 'Muhammad Farhan', 'nipp' => '2526176261', 'sales_location' => 'Stasiun Karawang'],

            ['name' => 'Nadhiska Putri Fadhillah', 'nipp' => '240102009972', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Nabila Zahrannisa', 'nipp' => '240102009955', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Rindi Berlian', 'nipp' => '240102009973', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Anggi Anugrah', 'nipp' => '240102009958', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Yunita Nabila Sanuri', 'nipp' => '240102009974', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Puspa Gita', 'nipp' => '240102009963', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Muhammad Fazrul Rohman', 'nipp' => '240102009953', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Pebria Sundari', 'nipp' => '240102009971', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Erico Yudaina', 'nipp' => '240402010749', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Olpia Anggraeni Haqqu', 'nipp' => '240202010028', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Melissa Shasya Bonita', 'nipp' => '240202010021', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Corry Aina', 'nipp' => '240502010839', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Jahran Pratiwi Putri Hermana', 'nipp' => '240502010838', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Revi Asyifa Dewi', 'nipp' => '240202010030', 'sales_location' => 'Stasiun Padalarang'],
            ['name' => 'Cheka Qobliansyah Ramadan', 'nipp' => '24010200967', 'sales_location' => 'Stasiun Padalarang'],

            ['name' => 'Kevin Rico Budiyono', 'nipp' => '2300697323', 'sales_location' => 'Stasiun Tegalluar Summarecon'],
        ];

        foreach ($operators as $operator) {
            DB::table('operators')->insert([
                'name' => $operator['name'],
                'nipp' => $operator['nipp'],
                'sales_location_id' => $locations[$operator['sales_location']],
            ]);
        }
    }
}
