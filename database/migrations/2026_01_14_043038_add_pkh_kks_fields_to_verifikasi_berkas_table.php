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
        Schema::table('verifikasi_berkas', function (Blueprint $table) {
            $table->string('status_pkh')->default('belum_upload')->after('status_kip');
            $table->text('catatan_pkh')->nullable()->after('catatan_kip');
            $table->string('status_kks')->default('belum_upload')->after('catatan_pkh');
            $table->text('catatan_kks')->nullable()->after('status_kks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_berkas', function (Blueprint $table) {
            $table->dropColumn(['status_pkh', 'catatan_pkh', 'status_kks', 'catatan_kks']);
        });
    }
};
