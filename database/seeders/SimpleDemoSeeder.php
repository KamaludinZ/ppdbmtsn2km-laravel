<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SimpleDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data demo lama
        DB::table('users')->where('email', 'LIKE', '%@demo.test')->delete();

        $this->command->info('🎯 Membuat akun demo sederhana...');

        // 1. Buat Admin
        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('  ✓ Admin: admin@demo.test / password');

        // 2. Buat Pendaftar
        User::create([
            'name' => 'Siswa Demo 1',
            'email' => 'siswa1@demo.test',
            'password' => Hash::make('password'),
            'role' => 'pendaftar',
            'email_verified_at' => now(),
        ]);

        $this->command->info('  ✓ Pendaftar: siswa1@demo.test / password');

        User::create([
            'name' => 'Siswa Demo 2',
            'email' => 'siswa2@demo.test',
            'password' => Hash::make('password'),
            'role' => 'pendaftar',
            'email_verified_at' => now(),
        ]);

        $this->command->info('  ✓ Pendaftar: siswa2@demo.test / password');

        // Display login info
        $this->command->info('');
        $this->command->line('╔════════════════════════════════════════════════════════════════╗');
        $this->command->line('║             ✨ AKUN DEMO BERHASIL DIBUAT! ✨                  ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║                                                                ║');
        $this->command->line('║  👤 ADMIN                                                      ║');
        $this->command->line('║     Email    : admin@demo.test                                 ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR 1                                                ║');
        $this->command->line('║     Email    : siswa1@demo.test                                ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('║  🎓 PENDAFTAR 2                                                ║');
        $this->command->line('║     Email    : siswa2@demo.test                                ║');
        $this->command->line('║     Password : password                                        ║');
        $this->command->line('║                                                                ║');
        $this->command->line('╠════════════════════════════════════════════════════════════════╣');
        $this->command->line('║  📝 Catatan:                                                   ║');
        $this->command->line('║  - Silakan lengkapi data pendaftar melalui aplikasi            ║');
        $this->command->line('║  - Buat jalur pendaftaran di menu admin                        ║');
        $this->command->line('║  - Lihat DEMO_SIMPLE.md untuk panduan lengkap                  ║');
        $this->command->line('╚════════════════════════════════════════════════════════════════╝');
        $this->command->info('');
    }
}
