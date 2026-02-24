<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'belum_diisi' to status_ukuran enum
        DB::statement("
            ALTER TABLE seragam
            DROP CONSTRAINT IF EXISTS seragam_status_ukuran_check
        ");

        DB::statement("
            ALTER TABLE seragam
            ADD CONSTRAINT seragam_status_ukuran_check
            CHECK (status_ukuran::text = ANY (ARRAY[
                'belum_diisi'::character varying,
                'menunggu'::character varying,
                'disetujui'::character varying,
                'ditolak'::character varying,
                'perbaiki'::character varying
            ]::text[]))
        ");

        // Add 'belum_diisi' to status_seragam enum
        DB::statement("
            ALTER TABLE seragam
            DROP CONSTRAINT IF EXISTS seragam_status_seragam_check
        ");

        DB::statement("
            ALTER TABLE seragam
            ADD CONSTRAINT seragam_status_seragam_check
            CHECK (status_seragam::text = ANY (ARRAY[
                'belum_diisi'::character varying,
                'menunggu'::character varying,
                'disetujui'::character varying,
                'ditolak'::character varying,
                'perbaiki'::character varying
            ]::text[]))
        ");
    }

    public function down(): void
    {
        // Revert status_ukuran to original enum values
        DB::statement("
            ALTER TABLE seragam
            DROP CONSTRAINT IF EXISTS seragam_status_ukuran_check
        ");

        DB::statement("
            ALTER TABLE seragam
            ADD CONSTRAINT seragam_status_ukuran_check
            CHECK (status_ukuran::text = ANY (ARRAY[
                'menunggu'::character varying,
                'disetujui'::character varying,
                'ditolak'::character varying,
                'perbaiki'::character varying
            ]::text[]))
        ");

        // Revert status_seragam to original enum values
        DB::statement("
            ALTER TABLE seragam
            DROP CONSTRAINT IF EXISTS seragam_status_seragam_check
        ");

        DB::statement("
            ALTER TABLE seragam
            ADD CONSTRAINT seragam_status_seragam_check
            CHECK (status_seragam::text = ANY (ARRAY[
                'menunggu'::character varying,
                'disetujui'::character varying,
                'ditolak'::character varying,
                'perbaiki'::character varying
            ]::text[]))
        ");
    }
};
