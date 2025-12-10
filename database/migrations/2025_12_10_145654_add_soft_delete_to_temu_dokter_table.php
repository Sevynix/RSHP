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
        if (Schema::hasTable('temu_dokter')) {
            Schema::table('temu_dokter', function (Blueprint $table) {
                if (!Schema::hasColumn('temu_dokter', 'deleted_at')) {
                    $table->timestamp('deleted_at')->nullable();
                }
                if (!Schema::hasColumn('temu_dokter', 'deleted_by')) {
                    $table->unsignedBigInteger('deleted_by')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('temu_dokter')) {
            Schema::table('temu_dokter', function (Blueprint $table) {
                if (Schema::hasColumn('temu_dokter', 'deleted_at')) {
                    $table->dropColumn('deleted_at');
                }
                if (Schema::hasColumn('temu_dokter', 'deleted_by')) {
                    $table->dropColumn('deleted_by');
                }
            });
        }
    }
};
