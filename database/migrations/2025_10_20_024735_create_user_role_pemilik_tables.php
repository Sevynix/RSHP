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
        // Create user table
        Schema::create('user', function (Blueprint $table) {
            $table->id('iduser');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
        });

        // Create role table
        Schema::create('role', function (Blueprint $table) {
            $table->id('idrole');
            $table->string('nama_role');
        });

        // Create role_user pivot table
        Schema::create('role_user', function (Blueprint $table) {
            $table->id('idroleuser');
            $table->unsignedBigInteger('iduser');
            $table->unsignedBigInteger('idrole');
            $table->enum('status', ['0', '1'])->default('1');
            
            $table->foreign('iduser')->references('iduser')->on('user')->onDelete('cascade');
            $table->foreign('idrole')->references('idrole')->on('role')->onDelete('cascade');
        });

        // Create pemilik table
        Schema::create('pemilik', function (Blueprint $table) {
            $table->id('idpemilik');
            $table->unsignedBigInteger('iduser');
            
            $table->foreign('iduser')->references('iduser')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilik');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('role');
        Schema::dropIfExists('user');
    }
};
