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
     * Create a new reservation from the admin panel (bypass tenant DB constraint).
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Map terrain_id from Blade to field_id for the Service
        if ($request->has('terrain_id')) {
            $data['field_id'] = $request->terrain_id;
        }

        // The UI uses mocked time slots with fake IDs (e.g. 9990+) if real slots aren't found.
        if (isset($data['time_slot_id']) && $data['time_slot_id'] >= 9000) {
            $data['time_slot_id'] = null;
        }

        // Fix time format
        if (isset($data['selected_time']) && isset($data['date'])) {
            $timePart = strlen($data['selected_time']) === 5 ? $data['selected_time'] . ':00' : $data['selected_time'];
            $data['selected_time'] = $data['date'] . ' ' . $timePart;
        }

        // Convert the uploaded CNI file to base64 so the Service handles it cleanly
        if ($request->hasFile('cni_image')) {
            $file = $request->file('cni_image');
            $data['cni_image_base64'] = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getPathname()));
        }

        // Fetch a user with a tenant to bypass the DB null constraint
        $defaultUser = \App\Models\User::whereHas('tenant')->first();

        // Create reservation via service
        $reservation = $this->reservationService->createReservation($data, $defaultUser);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $reservation], 201);
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
