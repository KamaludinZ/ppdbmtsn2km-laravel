<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to use raw SQL to modify check constraint
        // Drop the existing constraint
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");

        // Add new constraint with the additional role
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::character varying, 'pendaftar'::character varying, 'panitia_pendaftaran'::character varying, 'panitia_daftarulang'::character varying, 'panitia_tes_psikologi'::character varying, 'panitia_seragam'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove users with panitia_seragam role (if any) or update them
        DB::statement("UPDATE users SET role = 'panitia_daftarulang' WHERE role = 'panitia_seragam'");

        // Drop the constraint
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");

        // Restore original constraint without panitia_seragam
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::character varying, 'pendaftar'::character varying, 'panitia_pendaftaran'::character varying, 'panitia_daftarulang'::character varying, 'panitia_tes_psikologi'::character varying]::text[]))");
    }
};
