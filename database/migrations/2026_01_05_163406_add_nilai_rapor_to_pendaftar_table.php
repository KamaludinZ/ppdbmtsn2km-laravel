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
        Schema::table('pendaftar', function (Blueprint $table) {
            // Nilai Rapor Kelas 5 Semester 1
            $table->decimal('nilai_b_indo_5_1', 5, 2)->nullable()->after('prestasi');
            $table->decimal('nilai_matematika_5_1', 5, 2)->nullable()->after('nilai_b_indo_5_1');
            $table->decimal('nilai_ipa_5_1', 5, 2)->nullable()->after('nilai_matematika_5_1');

            // Nilai Rapor Kelas 5 Semester 2
            $table->decimal('nilai_b_indo_5_2', 5, 2)->nullable()->after('nilai_ipa_5_1');
            $table->decimal('nilai_matematika_5_2', 5, 2)->nullable()->after('nilai_b_indo_5_2');
            $table->decimal('nilai_ipa_5_2', 5, 2)->nullable()->after('nilai_matematika_5_2');

            // Nilai Rapor Kelas 6 Semester 1
            $table->decimal('nilai_b_indo_6_1', 5, 2)->nullable()->after('nilai_ipa_5_2');
            $table->decimal('nilai_matematika_6_1', 5, 2)->nullable()->after('nilai_b_indo_6_1');
            $table->decimal('nilai_ipa_6_1', 5, 2)->nullable()->after('nilai_matematika_6_1');

            // File scan rapor
            $table->string('file_rapor')->nullable()->after('nilai_ipa_6_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_b_indo_5_1', 'nilai_matematika_5_1', 'nilai_ipa_5_1',
                'nilai_b_indo_5_2', 'nilai_matematika_5_2', 'nilai_ipa_5_2',
                'nilai_b_indo_6_1', 'nilai_matematika_6_1', 'nilai_ipa_6_1',
                'file_rapor'
            ]);
        });
    }
};
