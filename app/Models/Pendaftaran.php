<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'siswa_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'nomor_wa',
        'foto',
        'id_jenis_sekolah',
        'jenjang',
        'kelas',
        'status_siswa',
        'nama_wali',
        'hp_wali',
        'alamat',
        'riwayat_kesehatan',
        'ukuran_baju',
        'catatan',
        'consent_wali',
        'kode_registrasi',
        'status_kehadiran',
        'waktu_checkin',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'consent_wali' => 'boolean',
            'waktu_checkin' => 'datetime',
            'siswa_id' => 'integer',
            'id_jenis_sekolah' => 'integer',
        ];
    }

    /**
     * Find a registration by siswa_id.
     */
    public function scopeBySiswaId($query, int $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Find a registration by kode_registrasi.
     */
    public function scopeByKodeRegistrasi($query, string $kode)
    {
        return $query->where('kode_registrasi', $kode);
    }

    /**
     * Check if student is already registered.
     */
    public static function isAlreadyRegistered(int $siswaId): bool
    {
        return static::where('siswa_id', $siswaId)->exists();
    }

    /**
     * Get existing registration for a student.
     */
    public static function getExistingRegistration(int $siswaId): ?self
    {
        return static::where('siswa_id', $siswaId)->first();
    }
}
