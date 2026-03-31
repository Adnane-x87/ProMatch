<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PublicFieldService;

class PublicFieldController extends Controller
{
    protected $publicFieldService;

    public function __construct(PublicFieldService $publicFieldService)
    {
        $this->publicFieldService = $publicFieldService;
    }

    public function index(Request $request)
    {
        // Pass query parameters (like city, date) to the service for searching
        $fields = $this->publicFieldService->searchFields($request->query());
        return response()->json(['success' => true, 'data' => $fields]);
    }

    public function show($id)
    {
        $field = $this->publicFieldService->getFieldDetailsWithSlots($id);
        return response()->json(['success' => true, 'data' => $field]);
    }
}