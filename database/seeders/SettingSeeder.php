<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Kontak Panitia (4 nomor)
            [
                'key' => 'kontak_panitia_1',
                'value' => '081234567890',
                'type' => 'string',
                'description' => 'Nomor kontak panitia 1',
            ],
            [
                'key' => 'kontak_panitia_2',
                'value' => '081234567891',
                'type' => 'string',
                'description' => 'Nomor kontak panitia 2',
            ],
            [
                'key' => 'kontak_panitia_3',
                'value' => '081234567892',
                'type' => 'string',
                'description' => 'Nomor kontak panitia 3',
            ],
            [
                'key' => 'kontak_panitia_4',
                'value' => '081234567893',
                'type' => 'string',
                'description' => 'Nomor kontak panitia 4',
            ],

            // Waktu Buka/Tutup Pendaftaran
            [
                'key' => 'registration_start_date',
                'value' => '2025-01-01 00:00:00',
                'type' => 'datetime',
                'description' => 'Tanggal dan waktu pembukaan pendaftaran',
            ],
            [
                'key' => 'registration_end_date',
                'value' => '2025-03-31 23:59:59',
                'type' => 'datetime',
                'description' => 'Tanggal dan waktu penutupan pendaftaran',
            ],

            // Mode/Status Pendaftaran
            [
                'key' => 'registration_phase',
                'value' => 'belum_dibuka',
                'type' => 'string',
                'description' => 'Fase pendaftaran saat ini (belum_dibuka, dibuka, verifikasi, tes_seleksi, pengumuman, daftar_ulang, selesai)',
            ],
            [
                'key' => 'registration_phase_label',
                'value' => 'Pendaftaran Belum Dibuka',
                'type' => 'string',
                'description' => 'Label fase pendaftaran yang ditampilkan',
            ],
            [
                'key' => 'registration_phase_description',
                'value' => 'Pendaftaran akan segera dibuka. Pantau terus website kami untuk informasi lebih lanjut.',
                'type' => 'text',
                'description' => 'Deskripsi fase pendaftaran yang ditampilkan',
            ],

            // Informasi Sekolah
            [
                'key' => 'school_name',
                'value' => 'MTsN 2 Kota Malang',
                'type' => 'string',
                'description' => 'Nama sekolah',
            ],
            [
                'key' => 'school_address',
                'value' => 'Jl. Raya Sulfat No. 01, Kota Malang, Jawa Timur 65152',
                'type' => 'string',
                'description' => 'Alamat sekolah',
            ],
            [
                'key' => 'school_email',
                'value' => 'info@mtsn2kotamalang.sch.id',
                'type' => 'string',
                'description' => 'Email sekolah',
            ],
            [
                'key' => 'school_phone',
                'value' => '(0341) 123456',
                'type' => 'string',
                'description' => 'Telepon sekolah',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
