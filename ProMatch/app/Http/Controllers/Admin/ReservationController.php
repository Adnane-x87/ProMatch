<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
