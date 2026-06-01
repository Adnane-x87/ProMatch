<?php

namespace App\Services;

use App\Models\Field;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
     * Get all fields belonging to a specific owner.
     *
     * @param int $ownerId
     * @return Collection
     */
    public function getFieldsByOwner(int $ownerId): Collection
    {
        return Field::where('owner_id', $ownerId)->get();
    }

    /**
     * Get a single field by ID.
     *
     * @param int $fieldId
     * @return Field
     */
    public function getFieldById(int $fieldId): Field
    {
        return Field::findOrFail($fieldId);
    }

    /**
     * Create a new field for the given owner, optionally with an image.
     *
     * @param array             $data
     * @param int               $ownerId
     * @param UploadedFile|null $image
     * @return Field
     */
    public function createField(array $data, int $ownerId, ?UploadedFile $image = null): Field
    {
        $data['owner_id'] = $ownerId;

        if ($image) {
            $data['image'] = $image->store('fields', 'public');
        }

        return Field::create($data);
    }

    /**
     * Update an existing field, optionally replacing the image.
     *
     * @param int               $fieldId
     * @param array             $data
     * @param UploadedFile|null $image
     * @return Field
     */
    public function updateField(int $fieldId, array $data, ?UploadedFile $image = null): Field
    {
        $field = Field::findOrFail($fieldId);

        if ($image) {
            // Delete the old image from storage if it exists
            if ($field->image && Storage::disk('public')->exists($field->image)) {
                Storage::disk('public')->delete($field->image);
            }
            $data['image'] = $image->store('fields', 'public');
        }

        $field->update($data);

        return $field;
    }

    /**
     * Delete a field by ID, removing its image from storage as well.
     *
     * @param int $fieldId
     * @return bool
     */
    public function deleteField(int $fieldId): bool
    {
        $field = Field::findOrFail($fieldId);

        if ($field->image && Storage::disk('public')->exists($field->image)) {
            Storage::disk('public')->delete($field->image);
        }

        return $field->delete();
    }

    /**
     * Add time slots to a field.
     *
     * @param int   $fieldId
     * @param array $data
     * @return mixed
     */
    public function addTimeSlots(int $fieldId, array $data)
    {
        $field = Field::findOrFail($fieldId);
        return $field->timeSlots()->createMany($data['slots'] ?? []);
    }
}

