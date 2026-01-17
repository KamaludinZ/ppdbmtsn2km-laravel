<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Connect to PostgreSQL directly to alter the enum type
        $connection = DB::connection('pgsql');
        
        // First, get the current enum values to check if "menunggu" already exists
        $result = $connection->select("
            SELECT enumlabel as value 
            FROM pg_enum 
            WHERE enumtypid = (SELECT oid FROM pg_type WHERE typname = 'pendaftar_status_pendaftaran_final')
            ORDER BY enumsortorder;
        ");
        
        $currentValues = array_map(function($row) {
            return $row->value;
        }, $result);
        
        // Check if "menunggu" is already in the enum
        if (!in_array('menunggu', $currentValues)) {
            // Add "menunggu" to the enum type
            $connection->statement("ALTER TYPE pendaftar_status_pendaftaran_final ADD VALUE IF NOT EXISTS 'menunggu'");
        }
        
        // Also add other common status values that might be missing
        $valuesToAdd = ['baru', 'diproses', 'dicabut', 'selesai'];
        
        foreach ($valuesToAdd as $value) {
            if (!in_array($value, $currentValues)) {
                $connection->statement("ALTER TYPE pendaftar_status_pendaftaran_final ADD VALUE IF NOT EXISTS '$value'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Removing enum values is not supported in PostgreSQL
        // We can't reverse this migration safely
        // Just log that reversal is not possible
        echo "Note: Enum values cannot be removed in PostgreSQL. Migration reversal is not possible.\n";
    }
};