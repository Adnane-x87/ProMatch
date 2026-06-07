<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\FieldService;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    protected $fieldService;
    protected $dashboardService;

    public function __construct(FieldService $fieldService, DashboardService $dashboardService)
    {
        $this->fieldService     = $fieldService;
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of fields belonging to the authenticated owner.
     */
    public function index()
    {
        $owner  = auth()->user()->owner ?? \App\Models\Owner::first();
        $fields = $this->fieldService->getFieldsByOwner($owner->id);

        $stats                   = $this->dashboardService->getDashboardStats();
        $pendingValidationsCount = $stats['pending_cnis'] ?? 0;

        return view('admin.fields', compact('fields', 'pendingValidationsCount'));
    }

    /**
     * Store a newly created field in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'address'        => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name.required' => 'Le nom du terrain est obligatoire.',
            'name.max' => 'Le nom du terrain ne doit pas depasser 255 caracteres.',
            'description.max' => 'La description ne doit pas depasser 1000 caracteres.',
            'address.required' => 'L adresse est obligatoire.',
            'address.max' => 'L adresse ne doit pas depasser 255 caracteres.',
            'price_per_hour.required' => 'Le prix par heure est obligatoire.',
            'price_per_hour.numeric' => 'Le prix par heure doit etre un nombre.',
            'price_per_hour.min' => 'Le prix par heure doit etre positif.',
            'image.image' => 'Le fichier doit etre une image.',
            'image.mimes' => 'L image doit etre au format jpeg, png, jpg, gif, svg ou webp.',
            'image.max' => 'L image ne doit pas depasser 2 Mo.',
        ]);

        $owner = auth()->user()->owner ?? \App\Models\Owner::first();

        $this->fieldService->createField(
            $request->only(['name', 'description', 'address', 'price_per_hour']),
            $owner->id,
            $request->hasFile('image') ? $request->file('image') : null
        );

        return redirect()->route('admin.fields.index')->with('success', 'Terrain créé avec succès.');
    }

    /**
     * Update the specified field in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'address'        => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name.required' => 'Le nom du terrain est obligatoire.',
            'name.max' => 'Le nom du terrain ne doit pas depasser 255 caracteres.',
            'description.max' => 'La description ne doit pas depasser 1000 caracteres.',
            'address.required' => 'L adresse est obligatoire.',
            'address.max' => 'L adresse ne doit pas depasser 255 caracteres.',
            'price_per_hour.required' => 'Le prix par heure est obligatoire.',
            'price_per_hour.numeric' => 'Le prix par heure doit etre un nombre.',
            'price_per_hour.min' => 'Le prix par heure doit etre positif.',
            'image.image' => 'Le fichier doit etre une image.',
            'image.mimes' => 'L image doit etre au format jpeg, png, jpg, gif, svg ou webp.',
            'image.max' => 'L image ne doit pas depasser 2 Mo.',
        ]);

        $this->fieldService->updateField(
            (int) $id,
            $request->only(['name', 'description', 'address', 'price_per_hour']),
            $request->hasFile('image') ? $request->file('image') : null
        );

        return redirect()->route('admin.fields.index')->with('success', 'Terrain mis à jour avec succès.');
    }

    /**
     * Remove the specified field from storage.
     */
    public function destroy($id)
    {
        $this->fieldService->deleteField((int) $id);

        return redirect()->route('admin.fields.index')->with('success', 'Terrain supprimé avec succès.');
    }
}
