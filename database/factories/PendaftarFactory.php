<?php

namespace Database\Factories;

use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pendaftar>
 */
class PendaftarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'jalur_pendaftaran_id' => JalurPendaftaran::first()->id ?? JalurPendaftaran::factory(),
            'nomor_pendaftaran' => 'PPDB-' . date('Y') . '-' . $this->faker->unique()->numberBetween(10000, 99999),
            
            // Data Pribadi
            'nisn' => $this->faker->unique()->numerify('##########'),
            'nama_lengkap' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'agama' => 'Islam',
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'foto' => null,

            // Data Orang Tua
            'nama_ayah' => $this->faker->name('male'),
            'nama_ibu' => $this->faker->name('female'),
            'pekerjaan_ayah' => $this->faker->jobTitle(),
            'pekerjaan_ibu' => $this->faker->jobTitle(),
            'penghasilan_ortu' => $this->faker->randomElement(['< 1 Juta', '1-3 Juta', '3-5 Juta', '5-10 Juta', '> 10 Juta']),
            'telepon_ortu' => $this->faker->phoneNumber(),

            // Data Pendidikan
            'asal_sekolah' => 'SDN ' . $this->faker->numberBetween(1, 10) . ' ' . $this->faker->city(),
            'nilai_rata_rata' => $this->faker->randomFloat(2, 70, 95),
            'prestasi' => $this->faker->optional(0.3)->sentence(), // 30% chance of having achievement

            // Status
            'status_pendaftaran' => $this->faker->randomElement(['menunggu_verifikasi', 'lengkapi_data', 'perbaiki_data', 'data_ditolak', 'data_disetujui']),
            'catatan_verifikasi' => null,
            'tanggal_daftar' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'tanggal_verifikasi' => null,
        ];
    }
}
