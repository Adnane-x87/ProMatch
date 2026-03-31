<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FieldService;

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
        $field = $this->fieldService->createField($request->all());
        return response()->json(['success' => true, 'data' => $field], 201);
    }

    public function show($id)
    {
        $field = $this->fieldService->getFieldById($id);
        return response()->json(['success' => true, 'data' => $field]);
    }

    public function update(Request $request, $id)
    {
        $field = $this->fieldService->updateField($id, $request->all());
        return response()->json(['success' => true, 'data' => $field]);
    }

    public function destroy($id)
    {
        $this->fieldService->deleteField($id);
        return response()->json(['success' => true, 'message' => 'Field deleted']);
    }

    public function addSlots(Request $request, $id)
    {
        $slots = $this->fieldService->addTimeSlots($id, $request->all());
        return response()->json(['success' => true, 'data' => $slots]);
    }
}