<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of fields.
     */
    public function index()
    {
        $owner = auth()->user()->owner ?? \App\Models\Owner::first();
        $fields = Field::where('owner_id', $owner->id)->get();

        // Get stats for the sidebar pending validations count
        $stats = $this->dashboardService->getDashboardStats();
        $pendingValidationsCount = $stats['pending_cnis'] ?? 0;

        return view('admin.fields', compact('fields', 'pendingValidationsCount'));
    }

    /**
     * Store a newly created field in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $owner = auth()->user()->owner ?? \App\Models\Owner::first();

        $data = $request->only(['name', 'description', 'address', 'price_per_hour']);
        $data['owner_id'] = $owner->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('fields', 'public');
            $data['image'] = $path;
        }

        Field::create($data);

        return redirect()->route('admin.fields.index')->with('success', 'Terrain créé avec succès.');
    }

    /**
     * Update the specified field in storage.
     */
    public function update(Request $request, $id)
    {
        $field = Field::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'address', 'price_per_hour']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($field->image && Storage::disk('public')->exists($field->image)) {
                Storage::disk('public')->delete($field->image);
            }
            $path = $request->file('image')->store('fields', 'public');
            $data['image'] = $path;
        }

        $field->update($data);

        return redirect()->route('admin.fields.index')->with('success', 'Terrain mis à jour avec succès.');
    }

    /**
     * Remove the specified field from storage.
     */
    public function destroy($id)
    {
        $field = Field::findOrFail($id);

        // Delete image file if exists
        if ($field->image && Storage::disk('public')->exists($field->image)) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Terrain supprimé avec succès.');
    }
}
