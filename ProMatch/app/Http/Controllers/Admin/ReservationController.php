<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Display a listing of reservations.
     */
    public function index()
    {
        $reservations = $this->reservationService->getAllReservations();
        return view('admin.reservations', compact('reservations'));
    }

    /**
     * Create a new reservation from the admin panel (bypass tenant DB constraint).
     */
    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $data['field_id'] = $data['terrain_id'];
        unset($data['terrain_id']);

        // Create reservation via service using the authenticated user
        $reservation = $this->reservationService->createReservation($data, auth()->user());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully',
                'data' => [
                    'id' => $reservation->id,
                    'status' => $reservation->status,
                    'request_date' => $reservation->request_date,
                    'start_time' => $reservation->start_time,
                ],
            ], 201);
        }

        return back()->with('success', 'Réservation créée avec succès.');
    }

    /**
     * Confirm a reservation.
     */
    public function confirm($id)
    {
        $this->reservationService->validateReservation($id);
        return back()->with('success', 'Réservation confirmée avec succès.');
    }

    /**
     * Cancel a reservation.
     */
    public function cancel($id)
    {
        $this->reservationService->cancelReservation($id);
        return back()->with('success', 'Réservation annulée.');
    }

    public function planning(Request $request)
    {
        $planning = $this->reservationService->getDailyPlanning($request->query('date'));
        return response()->json(['success' => true, 'data' => $planning]);
    }
}
