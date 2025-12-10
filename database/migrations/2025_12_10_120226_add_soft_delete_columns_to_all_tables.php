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
        // Add soft delete columns to user table (no foreign key)
        if (Schema::hasTable('user')) {
            Schema::table('user', function (Blueprint $table) {
                if (!Schema::hasColumn('user', 'deleted_at')) {
                    $table->timestamp('deleted_at')->nullable();
                }
                if (!Schema::hasColumn('user', 'deleted_by')) {
                    $table->unsignedBigInteger('deleted_by')->nullable();
                }
            });
        }

        // List of other tables to add soft delete columns with foreign key
        $tables = [
            'role',
            'role_user',
            'pemilik',
            'dokter',
            'perawat',
            'pet',
            'jenis_hewan',
            'ras_hewan',
            'kategori',
            'kategori_klinis',
            'kode_tindakan_terapi',
            'rekam_medis',
            'detail_rekam_medis',
            'reservasi_dokter',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'deleted_at')) {
                        $table->timestamp('deleted_at')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'deleted_by')) {
                        $table->unsignedBigInteger('deleted_by')->nullable()->comment('User ID yang menghapus');
                        // Note: Foreign key tidak ditambahkan untuk menghindari circular reference
                        // deleted_by akan direferensikan ke user.iduser secara manual
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'user',
            'role',
            'role_user',
            'pemilik',
            'dokter',
            'perawat',
            'pet',
            'jenis_hewan',
            'ras_hewan',
            'kategori',
            'kategori_klinis',
            'kode_tindakan_terapi',
            'rekam_medis',
            'detail_rekam_medis',
            'reservasi_dokter',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'deleted_at')) {
                        $table->dropColumn('deleted_at');
                    }
                    if (Schema::hasColumn($tableName, 'deleted_by')) {
                        $table->dropColumn('deleted_by');
                    }
                });
            }
        }
    }
};
