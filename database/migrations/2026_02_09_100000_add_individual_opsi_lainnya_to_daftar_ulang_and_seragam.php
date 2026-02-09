<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add individual opsi lainnya toggle columns to daftar_ulang table
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->boolean('opsi_lainnya_dropdown_individual')->default(false)->after('panitia_id');
            $table->boolean('opsi_lainnya_konfirmasi_individual')->default(false)->after('opsi_lainnya_dropdown_individual');
        });

        // Add individual opsi lainnya toggle columns to seragam table
        Schema::table('seragam', function (Blueprint $table) {
            $table->boolean('opsi_lainnya_dropdown_individual')->default(false)->after('petugas_id');
            $table->boolean('opsi_lainnya_konfirmasi_individual')->default(false)->after('opsi_lainnya_dropdown_individual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->dropColumn(['opsi_lainnya_dropdown_individual', 'opsi_lainnya_konfirmasi_individual']);
        });

        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn(['opsi_lainnya_dropdown_individual', 'opsi_lainnya_konfirmasi_individual']);
        });
    }
};
