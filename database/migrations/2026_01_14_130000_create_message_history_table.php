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
        Schema::create('message_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');
            $table->unsignedBigInteger('sender_id'); // admin/panitia yang mengirim
            $table->string('message_type'); // 'email', 'whatsapp', 'notification'
            $table->string('context'); // 'tes_psikologi', 'daftar_ulang', 'kelulusan', etc
            $table->text('message_content')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftar')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['pendaftar_id', 'context', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_history');
    }
};
