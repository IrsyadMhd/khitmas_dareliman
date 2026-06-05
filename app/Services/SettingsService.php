<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SettingsService
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/settings.json');
        
        if (!File::exists($this->path)) {
            $this->createDefaultSettings();
        }
    }

    /**
     * Get all settings
     */
    public function getSettings(): array
    {
        return json_decode(File::get($this->path), true);
    }

    /**
     * Update settings
     */
    public function updateSettings(array $newSettings): void
    {
        $settings = array_merge($this->getSettings(), $newSettings);
        File::put($this->path, json_encode($settings, JSON_PRETTY_PRINT));
    }

    /**
     * Check if Dareliman registration is currently open.
     */
    public function isDarelimanOpen(): bool
    {
        $settings = $this->getSettings();
        
        if ($settings['dareliman_mode'] === 'manual_open') return true;
        if ($settings['dareliman_mode'] === 'manual_closed') return false;
        
        // Mode: schedule
        return $this->isWithinSchedule($settings['dareliman_start'], $settings['dareliman_end']);
    }

    /**
     * Check if Umum registration is currently open.
     */
    public function isUmumOpen(): bool
    {
        $settings = $this->getSettings();
        
        if ($settings['umum_mode'] === 'manual_open') return true;
        if ($settings['umum_mode'] === 'manual_closed') return false;
        
        // Mode: schedule
        return $this->isWithinSchedule($settings['umum_start'], $settings['umum_end']);
    }

    /**
     * Helper to check schedule
     */
    private function isWithinSchedule(?string $start, ?string $end): bool
    {
        $now = Carbon::now('Asia/Jakarta');
        
        if ($start && $end) {
            return $now->between(Carbon::parse($start, 'Asia/Jakarta'), Carbon::parse($end, 'Asia/Jakarta'));
        } elseif ($start) {
            return $now->greaterThanOrEqualTo(Carbon::parse($start, 'Asia/Jakarta'));
        } elseif ($end) {
            return $now->lessThanOrEqualTo(Carbon::parse($end, 'Asia/Jakarta'));
        }
        
        // If no schedule set, assume closed
        return false;
    }

    /**
     * Create default settings file
     */
    private function createDefaultSettings(): void
    {
        $defaults = [
            'dareliman_mode' => 'manual_open', // manual_open, manual_closed, schedule
            'dareliman_start' => null,
            'dareliman_end' => null,
            'umum_mode' => 'manual_open',
            'umum_start' => null,
            'umum_end' => null,
        ];
        
        File::put($this->path, json_encode($defaults, JSON_PRETTY_PRINT));
    }
}
