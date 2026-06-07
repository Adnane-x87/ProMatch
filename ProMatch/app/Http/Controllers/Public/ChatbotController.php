<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\TimeSlot;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class ChatbotController extends Controller
{
    public function __construct(private ReservationService $reservationService)
    {
    }

    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:16'],
            'messages.*.role' => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:1000'],
            'state' => ['nullable', 'array'],
            'state.field_id' => ['nullable', 'integer', 'exists:fields,id'],
            'state.date' => ['nullable', 'date_format:Y-m-d'],
            'state.time' => ['nullable', 'date_format:H:i'],
        ]);

        if (! $request->user()) {
            return response()->json([
                'reply' => 'Connectez-vous d abord pour faire une reservation rapide.',
                'requires_auth' => true,
                'login_url' => route('login'),
                'state' => [],
            ]);
        }

        $fields = Field::query()
            ->orderBy('name')
            ->get(['id', 'name', 'price_per_hour']);

        if ($fields->isEmpty()) {
            return response()->json([
                'reply' => 'Aucun terrain n est disponible pour le moment.',
                'state' => [],
            ]);
        }

        $latestText = $this->latestUserText($validated['messages']);
        $state = $validated['state'] ?? [];

        return $this->advanceReservationFlow($latestText, $state, $fields, $request);
    }

    private function advanceReservationFlow(string $latestText, array $state, $fields, Request $request): JsonResponse
    {
        $field = $this->resolveField($state, $latestText, $fields);
        if (! $field) {
            return response()->json([
                'reply' => 'Quel terrain voulez-vous reserver ? ' . $this->formatFieldChoices($fields),
                'state' => [],
            ]);
        }

        $state['field_id'] = $field->id;

        $date = $this->resolveDate($state, $latestText);
        if (! $date) {
            return response()->json([
                'reply' => "Pour quelle date voulez-vous reserver {$field->name} ?",
                'state' => [
                    'field_id' => $field->id,
                ],
            ]);
        }

        $state['date'] = $date;
        $availableHours = $this->availableHours($field->id, $date);

        if ($availableHours === []) {
            return response()->json([
                'reply' => "Aucun creneau disponible pour {$field->name} le {$date}. Donnez une autre date.",
                'state' => [
                    'field_id' => $field->id,
                ],
                'available_hours' => [],
            ]);
        }

        $time = $this->resolveTime($state, $latestText, $availableHours);
        if (! $time) {
            return response()->json([
                'reply' => "Voici les heures disponibles pour {$field->name} le {$date}: " . implode(', ', $availableHours) . '. Quel creneau voulez-vous ?',
                'state' => [
                    'field_id' => $field->id,
                    'date' => $date,
                ],
                'available_hours' => $availableHours,
            ]);
        }

        $slot = $this->findAvailableSlot($field->id, $date, $time);
        if ($slot && $this->slotHasActiveReservation($slot)) {
            return response()->json([
                'reply' => "Ce creneau n est plus disponible. Choisissez une heure parmi: " . implode(', ', $availableHours) . '.',
                'state' => [
                    'field_id' => $field->id,
                    'date' => $date,
                ],
                'available_hours' => $availableHours,
            ]);
        }

        return $this->createQuickReservation($field, $date, $time, $slot, $request);
    }

    private function latestUserText(array $messages): string
    {
        $latest = collect($messages)
            ->reverse()
            ->firstWhere('role', 'user');

        return trim((string) ($latest['content'] ?? ''));
    }

    private function resolveField(array $state, string $text, $fields): ?Field
    {
        if (! empty($state['field_id'])) {
            return $fields->firstWhere('id', (int) $state['field_id']);
        }

        $normalizedText = $this->normalizeText($text);

        foreach ($fields as $field) {
            if (str_contains($normalizedText, $this->normalizeText($field->name))) {
                return $field;
            }

            if (preg_match('/(?:terrain\s*)?' . preg_quote((string) $field->id, '/') . '\b/i', $text)) {
                return $field;
            }
        }

        return null;
    }

    private function resolveDate(array $state, string $text): ?string
    {
        if (! empty($state['date'])) {
            return $this->normalizeDate($state['date']);
        }

        return $this->parseDateFromText($text);
    }

    private function resolveTime(array $state, string $text, array $availableHours): ?string
    {
        if (! empty($state['time']) && in_array($state['time'], $availableHours, true)) {
            return $state['time'];
        }

        $time = $this->parseTimeFromText($text);

        return $time && in_array($time, $availableHours, true) ? $time : null;
    }

    private function parseDateFromText(string $text): ?string
    {
        $normalizedText = $this->normalizeText($text);

        if (str_contains($normalizedText, 'demain')) {
            return now()->addDay()->toDateString();
        }

        if (str_contains($normalizedText, 'aujourdhui') || str_contains($normalizedText, 'aujourd hui')) {
            return now()->toDateString();
        }

        if (preg_match('/\b(\d{4})-(\d{2})-(\d{2})\b/', $text, $matches)) {
            return $this->normalizeDate("{$matches[1]}-{$matches[2]}-{$matches[3]}");
        }

        if (preg_match('/\b(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})\b/', $text, $matches)) {
            return $this->normalizeDate(sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]));
        }

        return null;
    }

    private function parseTimeFromText(string $text): ?string
    {
        if (preg_match('/\b([01]?\d|2[0-3])[:hH]([0-5]\d)\b/', $text, $matches)) {
            return sprintf('%02d:%02d', $matches[1], $matches[2]);
        }

        if (preg_match('/\b([01]?\d|2[0-3])\s*h\b/i', $text, $matches)) {
            return sprintf('%02d:00', $matches[1]);
        }

        return null;
    }

    private function normalizeDate(mixed $date): ?string
    {
        if (! is_string($date)) {
            return null;
        }

        try {
            $parsed = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (Throwable) {
            return null;
        }

        if ($parsed->isBefore(now()->startOfDay())) {
            return null;
        }

        return $parsed->toDateString();
    }

    private function availableHours(int $fieldId, string $date): array
    {
        $hours = TimeSlot::query()
            ->where('field_id', $fieldId)
            ->whereDate('date', $date)
            ->where('status', 'AVAILABLE')
            ->orderBy('start_time')
            ->get()
            ->reject(fn (TimeSlot $slot): bool => $this->slotHasActiveReservation($slot))
            ->map(fn (TimeSlot $slot): string => $this->formatSlotTime($slot->start_time))
            ->unique()
            ->values()
            ->all();

        return $hours ?: $this->fallbackHours();
    }

    private function findAvailableSlot(int $fieldId, string $date, string $time): ?TimeSlot
    {
        return TimeSlot::query()
            ->where('field_id', $fieldId)
            ->whereDate('date', $date)
            ->whereIn('start_time', [$time, $time . ':00'])
            ->where('status', 'AVAILABLE')
            ->get()
            ->first(fn (TimeSlot $slot): bool => ! $this->slotHasActiveReservation($slot));
    }

    private function createQuickReservation(Field $field, string $date, string $time, ?TimeSlot $slot, Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $reservation = $this->reservationService->createReservation([
                'field_id' => $field->id,
                'time_slot_id' => $slot?->id,
                'date' => $date,
                'selected_time' => "{$date} {$time}:00",
                'first_name' => $user->first_name ?: 'Client',
                'last_name' => $user->last_name ?: 'ProMatch',
                'email' => $user->email,
                'phone' => $user->phone ?: 'Non renseigne',
                'price' => $field->price_per_hour,
            ], $user);
        } catch (ValidationException $exception) {
            return response()->json([
                'reply' => collect($exception->errors())->flatten()->first() ?: 'Impossible de creer cette reservation.',
                'state' => [
                    'field_id' => $field->id,
                    'date' => $date,
                ],
            ], 422);
        } catch (Throwable) {
            return response()->json([
                'reply' => 'Impossible de creer cette reservation pour le moment.',
                'state' => [
                    'field_id' => $field->id,
                    'date' => $date,
                ],
            ], 500);
        }

        return response()->json([
            'reply' => "Demande envoyee pour {$field->name}, le {$date} a {$time}. Elle est en attente de validation.",
            'state' => [],
            'reservation' => [
                'id' => $reservation->id,
                'status' => $reservation->status,
                'field' => $field->name,
                'date' => $date,
                'time' => $time,
            ],
        ], 201);
    }

    private function formatFieldChoices($fields): string
    {
        return $fields
            ->map(fn (Field $field): string => "{$field->name} ({$field->price_per_hour} DH)")
            ->join(', ');
    }

    private function formatSlotTime(mixed $time): string
    {
        return Carbon::parse((string) $time)->format('H:i');
    }

    private function fallbackHours(): array
    {
        return ['08:00', '10:00', '14:00', '16:00', '18:00', '20:00'];
    }

    private function normalizeText(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
        $text = strtolower($text);

        return preg_replace('/[^a-z0-9]+/', ' ', $text) ?? $text;
    }

    private function slotHasActiveReservation(TimeSlot $slot): bool
    {
        return Reservation::where('time_slot_id', $slot->id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->exists();
    }
}
