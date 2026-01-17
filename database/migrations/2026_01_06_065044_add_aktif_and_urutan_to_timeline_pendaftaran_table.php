<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('timeline_pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('timeline_pendaftaran', 'aktif')) {
                $table->boolean('aktif')->default(true)->after('deskripsi');
            }
            if (!Schema::hasColumn('timeline_pendaftaran', 'urutan')) {
                $table->integer('urutan')->default(0)->after('aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timeline_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['aktif', 'urutan']);
        });
    }
};
