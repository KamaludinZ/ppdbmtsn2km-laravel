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
            $table->boolean('paling_populer')->default(false)->after('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jalur_pendaftaran', function (Blueprint $table) {
            $table->dropColumn('paling_populer');
        });
    }
};
