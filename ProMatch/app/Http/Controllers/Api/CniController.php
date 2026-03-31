<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CniService;

class CniController extends Controller
{
    protected $cniService;

    public function __construct(CniService $cniService)
    {
        $this->cniService = $cniService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'cni_document' => 'required|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $path = $this->cniService->uploadDocument($request->file('cni_document'), $request->user());
        
        return response()->json([
            'success' => true, 
            'message' => 'CNI uploaded successfully', 
            'path' => $path
        ]);
    }
}