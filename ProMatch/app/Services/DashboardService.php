<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Reservation;

class DashboardService
{
    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'total_clients' => Tenant::count(),
            'active_users' => Reservation::whereDate('request_date', '>=', now()->subDays(30))->distinct('tenant_id')->count('tenant_id'),
            'validated_cnis' => Tenant::where('is_cni_valid', true)->count(),
            'pending_cnis' => Tenant::whereNotNull('cni_document_url')->where('is_cni_valid', false)->count(),
            'todays_income' => Reservation::whereDate('start_time', now()->toDateString())
                ->where('status', 'APPROVED')
                ->sum('price')
        ];
    }
}
