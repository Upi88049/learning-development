<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus user yang sudah ada dengan email yang sama (untuk mencegah duplicate)
        DB::table('users')->whereIn('email', [
            'admin@example.com',
            'farhanmuhammad0312@gmail.com',
        ])->delete();

        // Masukkan data user baru
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Farhan',
                'email' => 'farhanmuhammad0312@gmail.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
