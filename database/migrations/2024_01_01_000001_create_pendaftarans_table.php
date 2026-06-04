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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siswa_id')->unique()->comment('ID siswa dari API Dareliman, unique untuk cegah duplikat');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('email');
            $table->string('nomor_wa')->nullable();
            $table->string('foto')->nullable()->comment('Nama file foto dari API');
            $table->unsignedInteger('id_jenis_sekolah')->nullable()->comment('ID jenis sekolah dari API');
            $table->string('jenjang')->comment('TAUD / TK / SD');
            $table->string('kelas')->nullable()->comment('Kelas spesifik, mis: 1, 2, A, B');
            $table->string('status_siswa');

            // Data wali
            $table->string('nama_wali');
            $table->string('hp_wali');
            $table->text('alamat');

            // Data medis & tambahan
            $table->text('riwayat_kesehatan')->nullable();
            $table->string('ukuran_baju')->nullable()->comment('S / M / L / XL / XXL');
            $table->text('catatan')->nullable();
            $table->boolean('consent_wali')->default(false);

            // QR & Kehadiran
            $table->string('kode_registrasi')->unique()->comment('UUID unik untuk QR Code');
            $table->enum('status_kehadiran', ['belum_hadir', 'hadir'])->default('belum_hadir');
            $table->timestamp('waktu_checkin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
