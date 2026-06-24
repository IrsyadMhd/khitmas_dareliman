<?php

namespace App\Services;

use App\Models\Pendaftaran;

class ScheduleService
{
    /**
     * Assigns a dynamic schedule to a new registration.
     * Rules: Starts at Jumat, 26/06/2026 09.00.
     * Every 20 people, increments hour by 1.
     * 
     * @return array ['hari' => string, 'jam' => string]
     */
    public function assignSchedule(): array
    {
        $hari = 'Jumat, 26/06/2026';
        
        // Count how many people already have this day assigned
        $count = Pendaftaran::where('jadwal_hari', $hari)->count();
        
        $baseHour = 9;
        $increment = (int) floor($count / 20);
        
        $assignedHour = $baseHour + $increment;
        $jamFormatted = sprintf('%02d.00', $assignedHour);
        
        return [
            'hari' => $hari,
            'jam' => $jamFormatted
        ];
    }
}
