<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add brosur settings to settings table
        Setting::set('brosur_file_path', null, 'string', 'Path to brochure PDF file');
        Setting::set('brosur_active', false, 'boolean', 'Show/hide brochure download button on public pages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove brosur settings
        Setting::where('key', 'brosur_file_path')->delete();
        Setting::where('key', 'brosur_active')->delete();
    }
};
