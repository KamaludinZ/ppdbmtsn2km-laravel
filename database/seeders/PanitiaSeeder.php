<?php

namespace Database\Seeders;

use App\Models\Panitia;
use App\Models\User;
use Illuminate\Database\Seeder;

class PanitiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $panitiaUsers = User::where('role', 'like', 'panitia%')->get();

        foreach ($panitiaUsers as $index => $user) {
            // Generate unique NIP based on user ID to avoid duplicates
            $nipSuffix = str_pad($user->id, 3, '0', STR_PAD_LEFT);
            $nip = '1981010120050' . $nipSuffix;

            Panitia::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $nip,
                    'nama_lengkap' => $user->name,
                    'role' => $user->role,
                    'telepon' => '08123456789' . $index,
                    'email' => $user->email,
                    'aktif' => true,
                ]
            );
        }
    }
}