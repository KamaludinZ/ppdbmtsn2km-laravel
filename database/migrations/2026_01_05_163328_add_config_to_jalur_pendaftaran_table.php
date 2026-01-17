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
        Schema::table('jalur_pendaftaran', function (Blueprint $table) {
            $table->decimal('nilai_minimum', 5, 2)->default(0)->after('kuota');
            $table->text('deskripsi_lengkap')->nullable()->after('nilai_minimum');
            $table->string('icon')->nullable()->after('persyaratan');
            $table->integer('urutan')->default(0)->after('warna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jalur_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['nilai_minimum', 'deskripsi_lengkap', 'icon', 'urutan']);
        });
    }
};
