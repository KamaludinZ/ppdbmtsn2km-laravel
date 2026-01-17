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
            if (!Schema::hasColumn('jalur_pendaftaran', 'prefix_nomor')) {
                $table->string('prefix_nomor', 10)->nullable()->after('kode');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jalur_pendaftaran', function (Blueprint $table) {
            if (Schema::hasColumn('jalur_pendaftaran', 'prefix_nomor')) {
                $table->dropColumn('prefix_nomor');
            }
        });
    }
};
