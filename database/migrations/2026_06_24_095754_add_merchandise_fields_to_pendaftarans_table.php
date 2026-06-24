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
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->enum('status_hadiah', ['belum', 'sudah'])->default('belum')->after('status_kehadiran');
            $table->timestamp('waktu_ambil_hadiah')->nullable()->after('status_hadiah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['status_hadiah', 'waktu_ambil_hadiah']);
        });
    }
};
