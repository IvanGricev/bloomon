<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;

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
     * Maximum number of orders per time slot
     * @var int
     */
    private const MAX_ORDERS_PER_SLOT = 5;

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

        // Get booked slots count for the selected date
        $bookedSlots = Order::where('delivery_date', $date)
            ->whereNotNull('delivery_time_slot')
            ->whereNotIn('status', ['cancelled'])
            ->groupBy('delivery_time_slot')
            ->selectRaw('delivery_time_slot, count(*) as count')
            ->pluck('count', 'delivery_time_slot')
            ->toArray();

        // Check availability for each time slot
        $availableSlots = [];
        foreach (self::TIME_SLOTS as $slot) {
            $bookedCount = $bookedSlots[$slot] ?? 0;
            if ($bookedCount < self::MAX_ORDERS_PER_SLOT) {
                $availableSlots[] = [
                    'slot' => $slot,
                    'available' => self::MAX_ORDERS_PER_SLOT - $bookedCount,
                    'total' => self::MAX_ORDERS_PER_SLOT
                ];
            }
        }

        return $availableSlots;
    }

    /**
     * Check if a specific time slot is available
     *
     * @param string $date
     * @param string $timeSlot
     * @return bool
     */
    public function isSlotAvailable(string $date, string $timeSlot): bool
    {
        // Validate time slot format
        if (!in_array($timeSlot, self::TIME_SLOTS)) {
            return false;
        }

        $selectedDate = Carbon::parse($date);

        // Basic date validation
        if ($selectedDate->isPast() || $selectedDate->isToday() || 
            $selectedDate->diffInDays(Carbon::now()) > 14) {
            return false;
        }

        // Check current bookings
        $bookedCount = Order::where('delivery_date', $date)
            ->where('delivery_time_slot', $timeSlot)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        return $bookedCount < self::MAX_ORDERS_PER_SLOT;
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

    /**
     * Get maximum orders per slot
     *
     * @return int
     */
    public function getMaxOrdersPerSlot(): int
    {
        return self::MAX_ORDERS_PER_SLOT;
    }
}