<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin users
        User::updateOrCreate(
            ['email' => 'admin@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'aktif' => true,
            ]
        );

        // Panitia Pendaftaran users
        User::updateOrCreate(
            ['email' => 'panitia1@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Pendaftaran 1',
                'password' => Hash::make('password'),
                'role' => 'panitia_pendaftaran',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'panitia2@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Pendaftaran 2',
                'password' => Hash::make('password'),
                'role' => 'panitia_pendaftaran',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'panitia3@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Pendaftaran 3',
                'password' => Hash::make('password'),
                'role' => 'panitia_pendaftaran',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'panitia4@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Pendaftaran 4',
                'password' => Hash::make('password'),
                'role' => 'panitia_pendaftaran',
                'aktif' => true,
            ]
        );

        // Panitia Daftar Ulang users
        User::updateOrCreate(
            ['email' => 'daftarulang1@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Daftar Ulang 1',
                'password' => Hash::make('password'),
                'role' => 'panitia_daftarulang',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'daftarulang2@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Daftar Ulang 2',
                'password' => Hash::make('password'),
                'role' => 'panitia_daftarulang',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'daftarulang3@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Daftar Ulang 3',
                'password' => Hash::make('password'),
                'role' => 'panitia_daftarulang',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'daftarulang4@mtsn2kotamalang.sch.id'],
            [
                'name' => 'Panitia Daftar Ulang 4',
                'password' => Hash::make('password'),
                'role' => 'panitia_daftarulang',
                'aktif' => true,
            ]
        );

        // Sample Pendaftar users
        User::updateOrCreate(
            ['email' => 'ahmad.maulana@example.com'],
            [
                'name' => 'Ahmad Maulana Yusuf',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti.aisyah@example.com'],
            [
                'name' => 'Siti Aisyah',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'muhammad.fauzi@example.com'],
            [
                'name' => 'Muhammad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'fatimah.azzahra@example.com'],
            [
                'name' => 'Fatimah Azzahra',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'rizki.hidayat@example.com'],
            [
                'name' => 'Rizki Hidayat',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'dewi.kartika@example.com'],
            [
                'name' => 'Dewi Kartika',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi.santoso@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'anisa.rahmawati@example.com'],
            [
                'name' => 'Anisa Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'agus.prasetyo@example.com'],
            [
                'name' => 'Agus Prasetyo',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'laila.nurhidayati@example.com'],
            [
                'name' => 'Laila Nurhidayati',
                'password' => Hash::make('password'),
                'role' => 'pendaftar',
                'aktif' => true,
            ]
        );
    }
}
