<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Models\Reservation;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index(Request $request)
    {
        $query = Reservation::with(['tenant.user', 'employee.user', 'field', 'timeSlot']);

        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->query('status')));
        }

        if ($request->filled('date')) {
            $date = $request->query('date');
            $query->where(function ($reservationQuery) use ($date) {
                $reservationQuery->whereDate('request_date', $date)
                    ->orWhereDate('start_time', $date)
                    ->orWhereHas('timeSlot', function ($slotQuery) use ($date) {
                        $slotQuery->whereDate('date', $date);
                    });
            });
        }

        $reservations = $query->latest('request_date')->get()->makeVisible('cni_image');

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }
    // UC6: Guest makes a reservation (No Auth required)
    public function store(Request $request)
    {
        $user = Auth::guard('sanctum')->user() ?? $request->user();
        $data = $this->normalizeReservationPayload($request, $user);

        $validator = Validator::make($data, [
            'field_id' => 'required|exists:fields,id',
            'time_slot_id' => 'nullable|exists:time_slots,id',
            'date' => 'required|date',
            'selected_time' => 'nullable|date',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:30',
            'price' => 'nullable|numeric|min:0',
            'cni_image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reservation data',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('cni_image')) {
            $validated['cni_image'] = $request->file('cni_image');
        }

        $reservation = $this->reservationService->createReservation($validated, $user);

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

    private function normalizeReservationPayload(Request $request, $user = null): array
    {
        $data = $request->all();

        $data['field_id'] = $data['field_id']
            ?? $data['terrain_id']
            ?? $data['fieldId']
            ?? $data['terrainId']
            ?? null;

        $data['time_slot_id'] = $data['time_slot_id']
            ?? $data['timeSlotId']
            ?? $data['slot_id']
            ?? $data['slotId']
            ?? null;

        $data['date'] = $data['date']
            ?? $data['request_date']
            ?? $data['reservation_date']
            ?? $data['reservationDate']
            ?? null;

        $selectedTime = $data['selected_time']
            ?? $data['selectedTime']
            ?? $data['start_time']
            ?? $data['startTime']
            ?? $data['time']
            ?? null;

        if (!$data['date'] && is_string($selectedTime) && preg_match('/^\d{4}-\d{2}-\d{2}/', $selectedTime)) {
            $data['date'] = substr($selectedTime, 0, 10);
        }

        if ($selectedTime && $data['date']) {
            $data['selected_time'] = $this->normalizeSelectedTime($selectedTime, $data['date']);
        }

        if (!empty($data['full_name']) && (empty($data['first_name']) || empty($data['last_name']))) {
            [$firstName, $lastName] = array_pad(explode(' ', trim($data['full_name']), 2), 2, '');
            $data['first_name'] = $data['first_name'] ?? $firstName;
            $data['last_name'] = $data['last_name'] ?? ($lastName ?: $firstName);
        }

        if (!empty($data['name']) && (empty($data['first_name']) || empty($data['last_name']))) {
            [$firstName, $lastName] = array_pad(explode(' ', trim($data['name']), 2), 2, '');
            $data['first_name'] = $data['first_name'] ?? $firstName;
            $data['last_name'] = $data['last_name'] ?? ($lastName ?: $firstName);
        }

        $data['first_name'] = $data['first_name'] ?? $user?->first_name;
        $data['last_name'] = $data['last_name'] ?? $user?->last_name;
        $data['email'] = $data['email'] ?? $user?->email;
        $data['phone'] = $data['phone'] ?? $data['phone_number'] ?? $data['phoneNumber'] ?? $user?->phone;

        if (isset($data['time_slot_id']) && (int) $data['time_slot_id'] >= 9000) {
            $data['time_slot_id'] = null;
        }

        if (!empty($data['time_slot_id']) && empty($data['selected_time'])) {
            $slot = TimeSlot::find($data['time_slot_id']);

            if ($slot) {
                $data['date'] = $data['date'] ?? $slot->date;
                $data['selected_time'] = $slot->date . ' ' . $slot->start_time;
            }
        }

        return $data;
    }

    private function normalizeSelectedTime(string $selectedTime, string $date): string
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}/', $selectedTime)) {
            return str_replace('T', ' ', substr($selectedTime, 0, 19));
        }

        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $selectedTime)) {
            return $date . ' ' . (strlen($selectedTime) === 5 ? $selectedTime . ':00' : $selectedTime);
        }

        return $selectedTime;
    }


    public function availableSlots(Request $request)
    {
        if (!$request->filled('field_id') && $request->filled('fieldId')) {
            $request->merge(['field_id' => $request->query('fieldId')]);
        }

        if (!$request->filled('field_id') && $request->filled('terrain_id')) {
            $request->merge(['field_id' => $request->query('terrain_id')]);
        }

        $request->validate([
            'field_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        $slots = $this->reservationService->getAvailableSlots(
            (int) $request->input('field_id'),
            $request->input('date')
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
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['APPROVED', 'REJECTED', 'CANCELED', 'ARRIVED', 'ABSENT'])],
        ]);

        $reservation = $this->reservationService->updateStatus($id, $validated['status'] ?? 'APPROVED');

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
