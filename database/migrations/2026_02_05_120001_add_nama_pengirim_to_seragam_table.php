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
        Schema::table('seragam', function (Blueprint $table) {
            $table->string('nama_pengirim_seragam')->nullable()->after('bukti_pembayaran_seragam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn('nama_pengirim_seragam');
        });
    }
};
