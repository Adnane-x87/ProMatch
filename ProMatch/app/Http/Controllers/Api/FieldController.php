<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FieldService;
use App\Models\Owner;

class FieldController extends Controller
{
    protected $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function index()
    {
        $fields = $this->fieldService->getAllFields();
        return response()->json(['success' => true, 'data' => $fields]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'owner_id' => 'nullable|exists:owners,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $ownerId = $this->resolveOwnerId($request);

        if (!$ownerId) {
            return response()->json([
                'success' => false,
                'message' => 'No owner account is available for this field.',
            ], 422);
        }

        $field = $this->fieldService->createField(
            collect($validated)->only(['name', 'description', 'address', 'price_per_hour'])->all(),
            $ownerId,
            $request->hasFile('image') ? $request->file('image') : null
        )->load('owner.user');

        return response()->json(['success' => true, 'data' => $field], 201);
    }

    public function show($id)
    {
        $field = $this->fieldService->getFieldById($id);
        return response()->json(['success' => true, 'data' => $field]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|required|string|max:255',
            'price_per_hour' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $field = $this->fieldService->updateField(
            (int) $id,
            collect($validated)->only(['name', 'description', 'address', 'price_per_hour'])->all(),
            $request->hasFile('image') ? $request->file('image') : null
        )->load('owner.user');

        return response()->json(['success' => true, 'data' => $field]);
    }

    public function destroy($id)
    {
        $this->fieldService->deleteField($id);
        return response()->json(['success' => true, 'message' => 'Field deleted']);
    }

    public function addSlots(Request $request, $id)
    {
        $request->validate([
            'slots' => 'required|array',
            'slots.*.date' => 'required|date',
            'slots.*.start_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'slots.*.end_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'slots.*.status' => 'nullable|in:AVAILABLE,RESERVED,UNAVAILABLE',
        ]);

        $slots = $this->fieldService->addTimeSlots($id, $request->all());
        return response()->json(['success' => true, 'data' => $slots]);
    }

    private function resolveOwnerId(Request $request): ?int
    {
        if ($request->filled('owner_id')) {
            return (int) $request->input('owner_id');
        }

        if ($request->user()?->owner) {
            return $request->user()->owner->id;
        }

        return Owner::query()->value('id');
    }
}
