<?php

namespace App\Services;

use Carbon\Carbon;

class DeliveryTimeService
{
    /**
     * Available time slots for delivery
     * @var array
     */
    private const TIME_SLOTS = [
        '09:00-12:00',
        '12:00-15:00',
        '15:00-18:00',
        '18:00-21:00'
    ];

    /**
     * Get available time slots for a specific date
     *
     * @param string $date
     * @return array
     */
    public function getAvailableTimeSlots(string $date): array
    {
        $selectedDate = Carbon::parse($date);
        
        // Don't allow dates in the past
        if ($selectedDate->isPast()) {
            return [];
        }

        // Don't allow same day delivery
        if ($selectedDate->isToday()) {
            return [];
        }

        // Don't allow dates more than 2 weeks in advance
        if ($selectedDate->diffInDays(Carbon::now()) > 14) {
            return [];
        }

        // Return all time slots for valid dates
        return array_map(function($slot) {
            return [
                'slot' => $slot
            ];
        }, self::TIME_SLOTS);
    }

    /**
     * Check if a specific time slot is valid
     *
     * @param string $date
     * @param string $timeSlot
     * @return bool
     */
    public function isValidTimeSlot(string $date, string $timeSlot): bool
    {
        // Validate time slot format
        if (!in_array($timeSlot, self::TIME_SLOTS)) {
            return false;
        }

        $selectedDate = Carbon::parse($date);

        // Basic date validation
        return !($selectedDate->isPast() || 
                $selectedDate->isToday() || 
                $selectedDate->diffInDays(Carbon::now()) > 14);
    }

    /**
     * Get all possible time slots
     *
     * @return array
     */
    public function getAllTimeSlots(): array
    {
        return self::TIME_SLOTS;
    }
}