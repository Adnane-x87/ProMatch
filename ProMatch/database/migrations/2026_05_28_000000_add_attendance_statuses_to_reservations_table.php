<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE reservations MODIFY status ENUM('PENDING', 'APPROVED', 'REJECTED', 'CANCELED', 'ARRIVED', 'ABSENT') NOT NULL DEFAULT 'PENDING'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('reservations')
                ->whereIn('status', ['ARRIVED', 'ABSENT'])
                ->update(['status' => 'APPROVED']);

            DB::statement("ALTER TABLE reservations MODIFY status ENUM('PENDING', 'APPROVED', 'REJECTED', 'CANCELED') NOT NULL DEFAULT 'PENDING'");
        }
    }
};
