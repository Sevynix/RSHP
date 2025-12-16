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
        if (!Schema::hasTable('kategori_klinis')) {
            Schema::create('kategori_klinis', function (Blueprint $table) {
                $table->id('idkategori_klinis');
                $table->string('nama_kategori_klinis', 100);
                $table->unsignedBigInteger('deleted_by')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        } else {
            // Drop foreign key constraints if they exist
            if (Schema::hasTable('detail_rekam_medis') && Schema::hasColumn('detail_rekam_medis', 'idkategori_klinis')) {
                try {
                    Schema::table('detail_rekam_medis', function (Blueprint $table) {
                        $table->dropForeign('fk_detail_rekam_medis_kategori_klinis1');
                    });
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            }
            
            if (Schema::hasTable('kode_tindakan_terapi') && Schema::hasColumn('kode_tindakan_terapi', 'idkategori_klinis')) {
                try {
                    Schema::table('kode_tindakan_terapi', function (Blueprint $table) {
                        $table->dropForeign('fk_kode_tindakan_terapi_kategori_klinis1');
                    });
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            }
            
            // Modify the idkategori_klinis column in kategori_klinis table to be auto-increment
            DB::statement('ALTER TABLE kategori_klinis MODIFY COLUMN idkategori_klinis BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            
            // Modify the idkategori_klinis column in detail_rekam_medis table to match (if column exists)
            if (Schema::hasTable('detail_rekam_medis') && Schema::hasColumn('detail_rekam_medis', 'idkategori_klinis')) {
                DB::statement('ALTER TABLE detail_rekam_medis MODIFY COLUMN idkategori_klinis BIGINT UNSIGNED NOT NULL');
                
                // Recreate the foreign key constraint
                Schema::table('detail_rekam_medis', function (Blueprint $table) {
                    $table->foreign('idkategori_klinis', 'fk_detail_rekam_medis_kategori_klinis1')
                          ->references('idkategori_klinis')->on('kategori_klinis')->onDelete('cascade');
                });
            }
            
            // Modify the idkategori_klinis column in kode_tindakan_terapi table to match (if column exists)
            if (Schema::hasTable('kode_tindakan_terapi') && Schema::hasColumn('kode_tindakan_terapi', 'idkategori_klinis')) {
                DB::statement('ALTER TABLE kode_tindakan_terapi MODIFY COLUMN idkategori_klinis BIGINT UNSIGNED NOT NULL');
                
                // Recreate the foreign key constraint
                Schema::table('kode_tindakan_terapi', function (Blueprint $table) {
                    $table->foreign('idkategori_klinis', 'fk_kode_tindakan_terapi_kategori_klinis1')
                          ->references('idkategori_klinis')->on('kategori_klinis')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if we created it
        if (Schema::hasTable('kategori_klinis')) {
            Schema::dropIfExists('kategori_klinis');
        }
    }
};
