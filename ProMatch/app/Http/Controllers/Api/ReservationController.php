<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReservationService;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    // UC6: User makes a reservation
    public function store(Request $request)
    {
        $reservation = $this->reservationService->createReservation($request->all(), $request->user());
        return response()->json(['success' => true, 'data' => $reservation], 201);
    }

    // UC4: Admin validates the reservation
    public function validateReservation(Request $request, $id)
    {
        // Expecting something like ['status' => 'approved' or 'rejected']
        $reservation = $this->reservationService->updateStatus($id, $request->status);
        return response()->json(['success' => true, 'data' => $reservation]);
    }

    // UC9: Staff/Admin views the schedule
    public function planning(Request $request)
    {
        $planning = $this->reservationService->getDailyPlanning($request->query('date'));
        return response()->json(['success' => true, 'data' => $planning]);
    }
}