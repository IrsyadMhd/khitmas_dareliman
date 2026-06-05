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
            $table->boolean('is_umum')->default(false)->after('id');
            $table->dropUnique('pendaftarans_siswa_id_unique');
            $table->string('siswa_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('is_umum');
            $table->unsignedInteger('siswa_id')->nullable(false)->unique()->change();
        });
    }
};
