<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('field_id')->nullable()->after('tenant_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time')->nullable()->after('field_id');
            $table->dateTime('end_time')->nullable()->after('start_time');
            $table->unsignedBigInteger('time_slot_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn(['field_id', 'start_time', 'end_time']);
            $table->unsignedBigInteger('time_slot_id')->nullable(false)->change();
        });
    }
};
