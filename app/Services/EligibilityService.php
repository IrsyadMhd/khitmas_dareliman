<?php

namespace App\Services;

class EligibilityService
{
    /**
     * Valid jenjang values for the khitanan massal event.
     */
    private const VALID_JENJANG = ['TAUD', 'TK', 'SD'];

    /**
     * Mapping of id_jenis_sekolah to jenjang name.
     * Update this mapping once confirmed with the Dareliman team.
     * For now, we treat null as "unknown" and let the user choose.
     */
    private const JENJANG_MAP = [
        1 => 'TAUD',
        2 => 'TK',
        3 => 'SD',
        4 => 'SMP',
        5 => 'SMA',
    ];

    /**
     * Check if a user is eligible for khitanan massal registration.
     *
     * @param array $user User data from the Dareliman API
     * @return array ['eligible' => bool, 'reason' => string|null]
     */
    public function checkEligibility(array $user): array
    {
        // Check 2: Status must be AKTIF
        $statusAktif = strtoupper($user['status_aktif'] ?? '');
        $statusSiswa = strtoupper($user['status_siswa'] ?? '');

        if ($statusAktif !== 'AKTIF' && $statusSiswa !== 'AKTIF') {
            return [
                'eligible' => false,
                'reason' => 'Akun siswa Anda tidak dalam status aktif. Silakan hubungi pihak Dareliman untuk informasi lebih lanjut.',
            ];
        }

        // Check 3: Jenjang must be TAUD, TK, or SD (if available from API)
        $idJenisSekolah = $user['id_jenis_sekolah'] ?? null;
        if ($idJenisSekolah !== null && isset(self::JENJANG_MAP[$idJenisSekolah])) {
            $jenjang = self::JENJANG_MAP[$idJenisSekolah];
            if (!in_array($jenjang, self::VALID_JENJANG)) {
                return [
                    'eligible' => false,
                    'reason' => 'Pendaftaran hanya terbuka untuk siswa jenjang TAUD, TK, dan SD. Jenjang Anda (' . $jenjang . ') tidak memenuhi syarat.',
                ];
            }
        }
        // If id_jenis_sekolah is null, we allow registration but require jenjang selection in the form

        return [
            'eligible' => true,
            'reason' => null,
        ];
    }

    /**
     * Resolve the jenjang from id_jenis_sekolah.
     *
     * @param int|null $idJenisSekolah
     * @return string|null
     */
    public function resolveJenjang(?int $idJenisSekolah): ?string
    {
        if ($idJenisSekolah === null) {
            return null;
        }

        return self::JENJANG_MAP[$idJenisSekolah] ?? null;
    }

    /**
     * Check if a jenjang value is valid.
     */
    public function isValidJenjang(string $jenjang): bool
    {
        return in_array(strtoupper($jenjang), self::VALID_JENJANG);
    }
}
