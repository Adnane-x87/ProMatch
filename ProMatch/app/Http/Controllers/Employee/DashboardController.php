<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\StaffService;

class DashboardController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * Display the employee dashboard.
     */
    public function index()
    {
        $employeeId = $this->employeeId();
        $reservations = $this->staffService->getDailySchedule($employeeId);
        $stats = $this->staffService->getDailyStats($employeeId);

        return view('employee.dashboard', compact('reservations', 'stats'));
    }

    /**
     * Mark a client as Arrived.
     */
    public function arrive($id)
    {
        $employeeId = $this->employeeId();
        $success = $this->staffService->verifyClientArrival((int) $id, $employeeId);
        $stats = $this->staffService->getDailyStats($employeeId);

        return response()->json([
            'success' => $success,
            'stats' => $stats,
        ]);
    }

    /**
     * Mark a client as Absent.
     */
    public function absent($id)
    {
        $employeeId = $this->employeeId();
        $success = $this->staffService->verifyClientAbsent((int) $id, $employeeId);
        $stats = $this->staffService->getDailyStats($employeeId);

        return response()->json([
            'success' => $success,
            'stats' => $stats,
        ]);
    }

    private function employeeId(): int
    {
        $employeeId = auth()->user()->employee?->id;

        abort_unless($employeeId, 403);

        return $employeeId;
    }
}
