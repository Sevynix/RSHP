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
        // Create dokter table
        Schema::create('dokter', function (Blueprint $table) {
            $table->id('id_dokter');
            $table->string('alamat', 100);
            $table->string('no_hp', 45);
            $table->string('bidang_dokter', 100);
            $table->char('jenis_kelamin', 1);
            $table->bigInteger('id_user');
            
            $table->foreign('id_user')->references('iduser')->on('user')->onDelete('cascade');
        });

        // Create perawat table
        Schema::create('perawat', function (Blueprint $table) {
            $table->id('id_perawat');
            $table->string('alamat', 100);
            $table->string('no_hp', 45);
            $table->char('jenis_kelamin', 1);
            $table->string('pendidikan', 100);
            $table->bigInteger('id_user');
            
            $table->foreign('id_user')->references('iduser')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawat');
        Schema::dropIfExists('dokter');
    }
};
