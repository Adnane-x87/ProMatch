<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientService;
    protected $dashboardService;

    public function __construct(ClientService $clientService, DashboardService $dashboardService)
    {
        $this->clientService    = $clientService;
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of clients (tenants).
     */
    public function index()
    {
        $clients = $this->clientService->getAllClients();
        $stats   = $this->dashboardService->getDashboardStats();

        $totalClients            = $stats['total_clients']  ?? $clients->count();
        $activeClients           = $stats['active_users']   ?? 0;
        $validatedCniCount       = $stats['validated_cnis'] ?? 0;
        $pendingValidationsCount = $stats['pending_cnis']   ?? 0;

        return view('admin.clients', compact(
            'clients',
            'totalClients',
            'activeClients',
            'validatedCniCount',
            'pendingValidationsCount'
        ));
    }

    /**
     * Block a tenant's user account.
     */
    public function block($id, Request $request)
    {
        $this->clientService->blockClient((int) $id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'is_blocked' => true]);
        }

        return redirect()->back()->with('success', 'Utilisateur bloqué avec succès.');
    }

    /**
     * Unblock a tenant's user account.
     */
    public function unblock($id, Request $request)
    {
        $this->clientService->unblockClient((int) $id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'is_blocked' => false]);
        }

        return redirect()->back()->with('success', 'Utilisateur débloqué avec succès.');
    }

    /**
     * Mark a tenant's CNI as validated.
     */
    public function validateCni($id)
    {
        $this->clientService->validateCni((int) $id);

        return redirect()->back()->with('success', 'CNI du client validé avec succès.');
    }
}
