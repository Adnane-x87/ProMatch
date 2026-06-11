<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\PublicFieldService;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function __construct(
        private readonly PublicFieldService $publicFieldService,
        private readonly ReservationService $reservationService,
        private readonly DashboardService $dashboardService,
    ) {
    }

    public function useCase()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'tenant' => [
                    'register' => 'POST /api/register',
                    'login' => 'POST /api/login',
                    'fields' => 'GET /api/mobile/fields',
                    'field_details' => 'GET /api/mobile/fields/{id}',
                    'available_slots' => 'GET /api/mobile/available-slots?field_id={id}&date=YYYY-MM-DD',
                    'reserve' => 'POST /api/mobile/reservations',
                    'upload_cni' => 'POST /api/mobile/cni/upload',
                    'my_reservations' => 'GET /api/mobile/reservations',
                    'sync' => 'GET /api/mobile/sync',
                ],
                'owner' => [
                    'reservations' => 'GET /api/mobile/admin/reservations',
                    'validate_reservation' => 'PUT /api/mobile/admin/reservations/{id}/validate',
                    'stats' => 'GET /api/mobile/admin/stats',
                ],
            ],
        ]);
    }

    public function fields(Request $request)
    {
        $fields = $this->publicFieldService
            ->searchFields($request->query('query'))
            ->loadMissing('owner.user');

        return response()->json([
            'success' => true,
            'data' => $fields->map(fn ($field) => $this->fieldPayload($field))->values(),
        ]);
    }

    public function fieldDetails(int $id)
    {
        $field = $this->publicFieldService->getFieldDetailsWithSlots($id);

        return response()->json([
            'success' => true,
            'data' => $this->fieldPayload($field, true),
        ]);
    }

    public function availableSlots(Request $request)
    {
        $request->merge([
            'field_id' => $request->input('field_id')
                ?? $request->input('fieldId')
                ?? $request->input('terrain_id')
                ?? $request->input('terrainId'),
        ]);

        $validated = $request->validate([
            'field_id' => 'required|integer|exists:fields,id',
            'date' => 'required|date',
        ]);

        $slots = $this->reservationService->getAvailableSlots(
            (int) $validated['field_id'],
            $validated['date']
        );

        return response()->json([
            'success' => true,
            'data' => $slots->map(fn ($slot) => $this->slotPayload($slot))->values(),
        ]);
    }

    public function myReservations(Request $request)
    {
        $reservations = $this->baseReservationQuery($request)
            ->where(function ($query) use ($request) {
                $tenantId = $request->user()->tenant?->id;

                if ($tenantId) {
                    $query->where('tenant_id', $tenantId);
                }

                $query->orWhere('email', $request->user()->email);
            })
            ->get()
            ->makeVisible('cni_image');

        return response()->json([
            'success' => true,
            'data' => $reservations->map(fn ($reservation) => $this->reservationPayload($reservation))->values(),
        ]);
    }

    public function ownerReservations(Request $request)
    {
        abort_unless($this->canSeeOwnerData($request->user()), 403);

        $reservations = $this->baseReservationQuery($request)
            ->when($request->user()->owner, function ($query) use ($request) {
                $query->whereHas('field', function ($fieldQuery) use ($request) {
                    $fieldQuery->where('owner_id', $request->user()->owner->id);
                });
            })
            ->get()
            ->makeVisible('cni_image');

        return response()->json([
            'success' => true,
            'data' => $reservations->map(fn ($reservation) => $this->reservationPayload($reservation))->values(),
        ]);
    }

    public function stats(Request $request)
    {
        abort_unless($this->canSeeOwnerData($request->user()), 403);

        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getDashboardStats(),
        ]);
    }

    public function sync(Request $request)
    {
        $fields = $this->publicFieldService->searchFields($request->query('query'))->loadMissing('owner.user');
        $reservations = $this->baseReservationQuery($request)
            ->where(function ($query) use ($request) {
                $tenantId = $request->user()->tenant?->id;

                if ($tenantId) {
                    $query->where('tenant_id', $tenantId);
                }

                $query->orWhere('email', $request->user()->email);
            })
            ->get()
            ->makeVisible('cni_image');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $this->userPayload($request->user()),
                'fields' => $fields->map(fn ($field) => $this->fieldPayload($field))->values(),
                'reservations' => $reservations->map(fn ($reservation) => $this->reservationPayload($reservation))->values(),
            ],
        ]);
    }

    private function baseReservationQuery(Request $request)
    {
        return Reservation::with(['field.owner.user', 'timeSlot', 'tenant.user'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', strtoupper($request->query('status')));
            })
            ->when($request->filled('date'), function ($query) use ($request) {
                $date = $request->query('date');

                $query->where(function ($dateQuery) use ($date) {
                    $dateQuery->whereDate('request_date', $date)
                        ->orWhereDate('start_time', $date)
                        ->orWhereHas('timeSlot', function ($slotQuery) use ($date) {
                            $slotQuery->whereDate('date', $date);
                        });
                });
            })
            ->orderByDesc('request_date')
            ->orderByDesc('created_at');
    }

    private function fieldPayload($field, bool $withSlots = false): array
    {
        $payload = [
            'id' => $field->id,
            'owner_id' => $field->owner_id,
            'name' => $field->name,
            'description' => $field->description,
            'address' => $field->address,
            'price_per_hour' => $field->price_per_hour,
            'image' => $field->image,
            'image_url' => $field->image_url,
            'owner' => $field->owner ? [
                'id' => $field->owner->id,
                'name' => trim(($field->owner->user?->first_name ?? '') . ' ' . ($field->owner->user?->last_name ?? '')),
                'email' => $field->owner->user?->email,
                'phone' => $field->owner->user?->phone,
            ] : null,
        ];

        if ($withSlots) {
            $payload['time_slots'] = $field->timeSlots
                ->map(fn ($slot) => $this->slotPayload($slot))
                ->values();
        }

        return $payload;
    }

    private function slotPayload($slot): array
    {
        return [
            'id' => $slot->id,
            'field_id' => $slot->field_id,
            'date' => $slot->date,
            'start_time' => $slot->start_time,
            'end_time' => $slot->end_time,
            'status' => $slot->status,
        ];
    }

    private function reservationPayload(Reservation $reservation): array
    {
        return [
            'id' => $reservation->id,
            'tenant_id' => $reservation->tenant_id,
            'field_id' => $reservation->field_id,
            'time_slot_id' => $reservation->time_slot_id,
            'first_name' => $reservation->first_name,
            'last_name' => $reservation->last_name,
            'email' => $reservation->email,
            'phone' => $reservation->phone,
            'request_date' => $reservation->request_date,
            'start_time' => $reservation->start_time,
            'end_time' => $reservation->end_time,
            'price' => $reservation->price,
            'status' => $reservation->status,
            'cni_image' => $reservation->cni_image,
            'cni_image_url' => $reservation->cni_image ? asset('storage/' . $reservation->cni_image) : null,
            'field' => $reservation->field ? $this->fieldPayload($reservation->field) : null,
            'time_slot' => $reservation->timeSlot ? $this->slotPayload($reservation->timeSlot) : null,
            'created_at' => $reservation->created_at,
            'updated_at' => $reservation->updated_at,
        ];
    }

    private function userPayload(User $user): array
    {
        $user->loadMissing(['tenant', 'owner', 'employee', 'roles']);

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'roles' => $user->roles->pluck('name')->values(),
            'tenant' => $user->tenant,
            'owner' => $user->owner,
            'employee' => $user->employee,
        ];
    }

    private function canSeeOwnerData(User $user): bool
    {
        return $user->owner !== null || $user->hasAnyRole(['owner', 'admin']);
    }
}
