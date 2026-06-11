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
            'cni_document' => 'required_without:cni_image|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'cni_image' => 'required_without:cni_document|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $path = $this->cniService->uploadDocument(
            $request->file('cni_document') ?? $request->file('cni_image'),
            $request->user()
        );
        
        return response()->json([
            'success' => true, 
            'message' => 'CNI uploaded successfully', 
            'data' => [
                'path' => $path,
            ],
            'path' => $path,
        ]);
    }
}
