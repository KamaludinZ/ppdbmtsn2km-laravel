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
        Schema::create('message_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->nullable()->constrained('pendaftar')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Admin yang queue

            // Message Details
            $table->enum('jenis_pesan', ['email', 'whatsapp'])->default('whatsapp');
            $table->string('penerima'); // Email address or phone number
            $table->string('judul')->nullable(); // Untuk email
            $table->text('isi_pesan');
            $table->string('template_name')->nullable(); // Nama template yang digunakan

            // Queue Management
            $table->enum('status', ['queued', 'processing', 'sent', 'failed', 'cancelled'])->default('queued');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->boolean('is_manual')->default(false); // Manual send vs automatic

            // Scheduling
            $table->timestamp('scheduled_at')->nullable(); // Kapan akan dikirim
            $table->timestamp('processed_at')->nullable(); // Kapan sudah diproses
            $table->integer('retry_count')->default(0);
            $table->integer('max_retries')->default(3);

            // Error Handling
            $table->text('error_message')->nullable();
            $table->text('error_trace')->nullable();
            $table->text('response_data')->nullable(); // Response dari API WhatsApp

            // Metadata
            $table->json('metadata')->nullable(); // Data tambahan (vars untuk template, dll)
            $table->text('notes')->nullable(); // Catatan admin

            $table->timestamps();

            // Indexes for performance
            $table->index(['status', 'scheduled_at']);
            $table->index(['jenis_pesan', 'status']);
            $table->index('pendaftar_id');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_queue');
    }
};
