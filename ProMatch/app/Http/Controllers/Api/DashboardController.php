<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get dashboard statistics.
     */
    public function stats()
    {
        $stats = $this->dashboardService->getDashboardStats();
        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Get all time slots.
     */
    public function getSlots(Request $request)
    {
        $query = TimeSlot::with('field');

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        if ($request->has('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        $slots = $query->get();

        return response()->json(['success' => true, 'data' => $slots]);
    }

    /**
     * Create a new time slot.
     */
    public function storeSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $slot = TimeSlot::create($request->all());

        return response()->json(['success' => true, 'data' => $slot], 201);
    }

    /**
     * Update a time slot.
     */
    public function updateSlot(Request $request, $id)
    {
        $slot = TimeSlot::find($id);

        if (!$slot) {
            return response()->json(['success' => false, 'message' => 'Slot not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'field_id' => 'sometimes|exists:fields,id',
            'date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'status' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $slot->update($request->all());

        return response()->json(['success' => true, 'data' => $slot]);
    }

    /**
     * Delete a time slot.
     */
    public function destroySlot($id)
    {
        $slot = TimeSlot::find($id);

        if (!$slot) {
            return response()->json(['success' => false, 'message' => 'Slot not found'], 404);
        }

        $slot->delete();

        return response()->json(['success' => true, 'message' => 'Slot deleted successfully']);
    }
}
