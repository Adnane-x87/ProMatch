<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tenants', 'cin') && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE tenants MODIFY cin VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tenants', 'cin') && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE tenants MODIFY cin VARCHAR(255) NOT NULL');
        }
    }
};
