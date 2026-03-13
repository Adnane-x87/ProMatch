<?php

namespace App\Services;

use App\Models\Field;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Collection;

class PublicFieldService
{
    /**
     * Search for fields by name or address.
     *
     * @param string|null $query
     * @return Collection
     */
    public function searchFields(?string $query = null): Collection
    {
        if (empty($query)) {
            return Field::all();
        }

        return Field::where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->get();
    }

    /**
     * Get detailed information about a specific field.
     *
     * @param int $fieldId
     * @return Field
     */
    public function getFieldDetails(int $fieldId): Field
    {
        return Field::with(['owner.user'])->findOrFail($fieldId);
    }

    /**
     * Get available time slots for a field on a specific date.
     *
     * @param int $fieldId
     * @param string $date
     * @return Collection
     */
    public function getAvailableSlots(int $fieldId, string $date): Collection
    {
        return TimeSlot::where('field_id', $fieldId)
            ->whereDate('date', $date)
            ->where('status', 'AVAILABLE')
            ->get();
    }
}
