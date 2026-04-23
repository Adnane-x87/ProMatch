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

    // UC6: Guest makes a reservation (No Auth required)
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

        if ($request->hasFile('cni_image')) {
            $data['cni_image'] = $request->file('cni_image');
        }

        // Create reservation via service directly from request (user defaults to null inside service)
        $reservation = $this->reservationService->createReservation($data);

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


    public function availableSlots(Request $request)
    {
        $request->validate([
            'field_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        $slots = $this->reservationService->getAvailableSlots(
            $request->query('field_id'),
            $request->query('date')
        );

        // If no real slots are found, generate some fake ones to facilitate UI testing
        if ($slots->isEmpty()) {
            $fakeSlots = [];
            $startTimes = ['08:00', '10:00', '14:00', '16:00', '18:00', '20:00'];
            
            foreach ($startTimes as $index => $time) {
                $endHour = (int)substr($time, 0, 2) + 2;
                $fakeSlots[] = [
                    'id' => 9990 + $index, // Mocked ID
                    'field_id' => (int)$request->query('field_id'),
                    'date' => $request->query('date'),
                    'start_time' => $time,
                    'end_time' => sprintf('%02d:00', $endHour),
                    'status' => 'AVAILABLE'
                ];
            }
            
            return response()->json(['success' => true, 'data' => $fakeSlots], 200);
        }

        return response()->json(['success' => true, 'data' => $slots], 200);
    }

    // UC4: Admin validates the reservation
    public function validateReservation(Request $request, $id)
    {
        $reservation = $this->reservationService->updateStatus($id, $request->status);
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $reservation->id,
                'status' => $reservation->status,
            ],
        ]);
    }

    // UC9: Staff/Admin views the schedule
    public function planning(Request $request)
    {
        $planning = $this->reservationService->getDailyPlanning($request->query('date'));
        return response()->json(['success' => true, 'data' => $planning]);
    }
}
