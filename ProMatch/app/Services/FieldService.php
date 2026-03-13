<?php

namespace App\Services;

use App\Models\Field;
use Illuminate\Database\Eloquent\Collection;

class FieldService
{
    /**
     * Get all fields with their owners.
     *
     * @return Collection
     */
    public function getAllFields(): Collection
    {
        return Field::with('owner.user')->get();
    }

    /**
     * Delete a field by ID.
     *
     * @param int $fieldId
     * @return bool
     */
    public function deleteField(int $fieldId): bool
    {
        $field = Field::findOrFail($fieldId);
        return $field->delete();
    }
}
