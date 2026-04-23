<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;

class DashboardService
{
    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        $today = now()->toDateString();
        
        return [
            'total_clients' => Tenant::count(),
            'active_users' => Reservation::whereDate('request_date', '>=', now()->subDays(30))->distinct('tenant_id')->count('tenant_id'),
            'active_reservations' => Reservation::whereIn('status', ['PENDING', 'APPROVED'])->count(),
            'validated_cnis' => Tenant::where('is_cni_valid', true)->count(),
            'pending_cnis' => $this->getPendingCniReservationsQuery()->count(),
            'todays_income' => Reservation::whereDate('request_date', $today)
                ->where('status', 'APPROVED')
                ->sum('price'),
            'todays_reservations' => Reservation::whereDate('request_date', $today)->count()
        ];
    }

    public function getRecentReservations(int $limit = 5)
    {
        return Reservation::with('field')
            ->whereIn('status', ['PENDING', 'APPROVED', 'REJECTED', 'CANCELED'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getPendingCniTasks(int $limit = 3): array
    {
        return $this->getPendingCniReservationsQuery()
            ->with('field')
            ->limit($limit)
            ->get()
            ->map(function (Reservation $reservation) {
                return [
                    'id' => $reservation->id,
                    'first_name' => $reservation->first_name,
                    'last_name' => $reservation->last_name,
                    'field_name' => $reservation->field?->name,
                    'request_date' => $reservation->request_date,
                    'status' => $reservation->status,
                    'cni_image_url' => $reservation->cni_image ? Storage::url($reservation->cni_image) : null,
                ];
            })
            ->values()
            ->all();
    }

    private function getPendingCniReservationsQuery()
    {
        return Reservation::query()
            ->whereNotNull('cni_image')
            ->where('status', 'PENDING')
            ->where(function ($query) {
                $query->whereNull('tenant_id')
                    ->orWhereHas('tenant', function ($tenantQuery) {
                        $tenantQuery->where('is_cni_valid', false);
                    });
            })
            ->orderByDesc('created_at');
    }
}
