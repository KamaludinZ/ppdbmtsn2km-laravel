<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jalur_pendaftaran', function (Blueprint $blueprint) {
            // Kita gunakan longText untuk menampung konten HTML dari WYSIWYG
            if (!Schema::hasColumn('jalur_pendaftaran', 'konten_html')) {
                $blueprint->longText('konten_html')->nullable()->after('deskripsi_lengkap');
            }
            // Kolom untuk aset tambahan (gambar/dokumen dalam format JSON)
            if (!Schema::hasColumn('jalur_pendaftaran', 'aset_jalur')) {
                $blueprint->json('aset_jalur')->nullable()->after('konten_html');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jalur_pendaftaran', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['konten_html', 'aset_jalur']);
        });
    }
};