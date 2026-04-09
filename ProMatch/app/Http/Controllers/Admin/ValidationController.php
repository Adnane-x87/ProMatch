<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CniService;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    protected $cniService;

    public function __construct(CniService $cniService)
    {
        $this->cniService = $cniService;
    }

    /**
     * Display a listing of pending CNI validations.
     */
    public function index()
    {
        $pendingValidations = $this->cniService->getPendingCNIs();
        $pendingValidationsCount = $pendingValidations->count();
        
        return view('admin.validations', compact('pendingValidations', 'pendingValidationsCount'));
    }

    /**
     * Approve a CNI validation.
     */
    public function approve($tenantId)
    {
        $this->cniService->verifyCNI($tenantId, true);
        return back()->with('success', 'CNI validé avec succès.');
    }

    /**
     * Reject a CNI validation.
     */
    public function reject($tenantId)
    {
        $this->cniService->verifyCNI($tenantId, false);
        return back()->with('success', 'CNI rejeté.');
    }
}
