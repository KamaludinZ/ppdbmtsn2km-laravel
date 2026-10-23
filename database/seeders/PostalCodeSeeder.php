<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFile = database_path('seeders/sql/postal_codes.sql');

        if (!File::exists($sqlFile)) {
            $this->command->error("File postal_codes.sql tidak ditemukan di database/seeders/sql/");
            $this->command->info("Pastikan file database/seeders/sql/postal_codes.sql ada (ambil dari repository)");
            return;
        }

        $this->command->info('Importing postal codes data...');

        // Read SQL file
        $sql = File::get($sqlFile);

        // Execute SQL
        try {
            DB::unprepared($sql);

            $count = DB::table('postal_codes')->count();
            $this->command->info("✓ Berhasil import {$count} kode pos");
        } catch (\Exception $e) {
            $this->command->error("Error: " . $e->getMessage());
        }
    }
}
