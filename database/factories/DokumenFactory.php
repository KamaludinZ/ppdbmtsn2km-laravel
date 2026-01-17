<?php

namespace Database\Factories;

use App\Models\Pendaftar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dokumen>
 */
class DokumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pendaftar_id' => Pendaftar::factory(),
            'jenis_dokumen' => $this->faker->randomElement(['kk', 'akta_kelahiran', 'raport']),
            'nama_file' => $this->faker->word() . '.pdf',
            'path_file' => 'documents/' . $this->faker->uuid() . '.pdf',
            'mime_type' => 'application/pdf',
            'ukuran_file' => $this->faker->numberBetween(100000, 2000000),
            'status_verifikasi' => $this->faker->randomElement(['menunggu', 'diterima', 'ditolak']),
            'catatan' => $this->faker->optional()->sentence(),
            'tanggal_upload' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'tanggal_verifikasi' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
