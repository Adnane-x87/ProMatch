<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = $this->dashboardService->getDashboardStats();
        $recentReservations = $this->dashboardService->getRecentReservations();
        $pendingCniTasks = $this->dashboardService->getPendingCniTasks();

        return view('admin.dashboard', compact('stats', 'recentReservations', 'pendingCniTasks'));
    }

    public function stats()
    {
        $stats = $this->dashboardService->getDashboardStats();
        return response()->json(['success' => true, 'data' => $stats]);
    }
}
