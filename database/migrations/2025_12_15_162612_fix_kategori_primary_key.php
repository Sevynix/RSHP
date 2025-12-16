<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists, if not create it
        if (!Schema::hasTable('kategori')) {
            Schema::create('kategori', function (Blueprint $table) {
                $table->id('idkategori');
                $table->string('nama_kategori', 100);
                $table->unsignedBigInteger('deleted_by')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        } else {
            // Drop foreign key constraint from kode_tindakan_terapi table if it exists
            try {
                Schema::table('kode_tindakan_terapi', function (Blueprint $table) {
                    $table->dropForeign('fk_kode_tindakan_terapi_kategori1');
                });
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            // Modify the idkategori column in kategori table to be auto-increment
            DB::statement('ALTER TABLE kategori MODIFY COLUMN idkategori BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            
            // Modify the idkategori column in kode_tindakan_terapi table to match
            DB::statement('ALTER TABLE kode_tindakan_terapi MODIFY COLUMN idkategori BIGINT UNSIGNED NOT NULL');
            
            // Recreate the foreign key constraint
            Schema::table('kode_tindakan_terapi', function (Blueprint $table) {
                $table->foreign('idkategori', 'fk_kode_tindakan_terapi_kategori1')
                      ->references('idkategori')->on('kategori')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if we created it
        if (Schema::hasTable('kategori')) {
            // Check if table has only default structure
            Schema::dropIfExists('kategori');
        }
    }
};
