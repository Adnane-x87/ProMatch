<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of clients (tenants).
     */
    public function index()
    {
        $clients = Tenant::with('user')->get();
        
        // Get stats for the top cards
        $stats = $this->dashboardService->getDashboardStats();
        
        $totalClients = $stats['total_clients'] ?? $clients->count();
        $activeClients = $stats['active_users'] ?? 0;
        $validatedCniCount = $stats['validated_cnis'] ?? 0;
        $pendingValidationsCount = $stats['pending_cnis'] ?? 0;

        return view('admin.clients', compact(
            'clients', 
            'totalClients', 
            'activeClients', 
            'validatedCniCount', 
            'pendingValidationsCount'
        ));
    }
}
