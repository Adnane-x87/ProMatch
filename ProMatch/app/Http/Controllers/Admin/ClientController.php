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
        // Eager load relationships, counts, and sums to prevent N+1 queries and massively speed up the page
        $clients = Tenant::with('user')
            ->withCount('reservations')
            ->withSum('reservations', 'price')
            ->get();
        
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

    public function block($id, Request $request)
    {
        $tenant = Tenant::with('user')->findOrFail($id);
        $user = $tenant->user;
        $owner = \App\Models\Owner::first(); // Assuming first owner or authenticated owner
        
        if ($owner) {
            $owner->blockUser($user);
        } else {
            $user->is_blocked = true;
            $user->save();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'is_blocked' => true]);
        }
        return redirect()->back()->with('success', 'Utilisateur bloqué avec succès.');
    }

    public function unblock($id, Request $request)
    {
        $tenant = Tenant::with('user')->findOrFail($id);
        $user = $tenant->user;
        $owner = \App\Models\Owner::first(); // Assuming first owner or authenticated owner
        
        if ($owner) {
            $owner->unblockUser($user);
        } else {
            $user->is_blocked = false;
            $user->save();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'is_blocked' => false]);
        }
        return redirect()->back()->with('success', 'Utilisateur débloqué avec succès.');
    }

    public function validateCni($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_cni_valid = true;
        $tenant->save();

        return redirect()->back()->with('success', 'CNI du client validé avec succès.');
    }
}
