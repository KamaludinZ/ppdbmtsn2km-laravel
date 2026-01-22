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
    public function up()
    {
        // Index untuk optimasi pencarian NISN yang sering dilakukan
        Schema::table('pendaftar', function (Blueprint $table) {
            if (!$this->indexExists('pendaftar', 'idx_pendaftar_nisn')) {
                $table->index('nisn', 'idx_pendaftar_nisn');
            }
            if (!$this->indexExists('pendaftar', 'idx_pendaftar_nsm_sekolah_asal')) {
                $table->index('nsm_sekolah_asal', 'idx_pendaftar_nsm_sekolah_asal');
            }
            if (!$this->indexExists('pendaftar', 'idx_pendaftar_npsn_sekolah_asal')) {
                $table->index('npsn_sekolah_asal', 'idx_pendaftar_npsn_sekolah_asal');
            }
            if (!$this->indexExists('pendaftar', 'idx_pendaftar_jalur_id')) {
                $table->index('jalur_pendaftaran_id', 'idx_pendaftar_jalur_id');
            }
            if (!$this->indexExists('pendaftar', 'idx_pendaftar_created_at')) {
                $table->index('created_at', 'idx_pendaftar_created_at');
            }
        });

        // Index untuk optimasi pencarian email
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'idx_users_role')) {
                $table->index('role', 'idx_users_role');
            }
        });

        // Index untuk jalur pendaftaran
        Schema::table('jalur_pendaftaran', function (Blueprint $table) {
            if (!$this->indexExists('jalur_pendaftaran', 'idx_jalur_pendaftaran_aktif_urutan')) {
                $table->index(['aktif', 'urutan'], 'idx_jalur_pendaftaran_aktif_urutan');
            }
            if (!$this->indexExists('jalur_pendaftaran', 'idx_jalur_pendaftaran_kode')) {
                $table->index('kode', 'idx_jalur_pendaftaran_kode');
            }
        });

        // Composite indexes untuk query yang sering digunakan
        Schema::table('pendaftar', function (Blueprint $table) {
            if (!$this->indexExists('pendaftar', 'idx_pendaftar_status_jalur')) {
                $table->index(['status_pendaftaran', 'jalur_pendaftaran_id'], 'idx_pendaftar_status_jalur');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropIndex(['nisn']);
            $table->dropIndex(['nsm_sekolah_asal']);
            $table->dropIndex(['npsn_sekolah_asal']);
            $table->dropIndex(['jalur_pendaftaran_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status_pendaftaran', 'jalur_pendaftaran_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });

        Schema::table('jalur_pendaftaran', function (Blueprint $table) {
            $table->dropIndex(['aktif', 'urutan']);
            $table->dropIndex(['kode']);
        });
    }

    /**
     * Check if index exists (compatible with PostgreSQL and MySQL)
     */
    private function indexExists($table, $index)
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL
            $result = DB::select(
                "SELECT COUNT(*) as count
                 FROM pg_indexes
                 WHERE tablename = ?
                 AND indexname = ?",
                [$table, $index]
            );
        } else {
            // MySQL and others
            $dbName = DB::getDatabaseName();
            $result = DB::select(
                "SELECT COUNT(*) as count
                 FROM information_schema.statistics
                 WHERE table_schema = ?
                 AND table_name = ?
                 AND index_name = ?",
                [$dbName, $table, $index]
            );
        }

        return $result[0]->count > 0;
    }
};