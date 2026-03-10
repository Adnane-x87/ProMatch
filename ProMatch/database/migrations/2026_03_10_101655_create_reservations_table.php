<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
    $table->foreignId('time_slot_id')->unique()->constrained()->onDelete('cascade');
    $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
    $table->string('first_name');
    $table->string('last_name');
    $table->string('email');
    $table->string('phone');
    $table->dateTime('request_date');
    $table->float('price');
    $table->string('cni_url')->nullable();
    $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'CANCELED'])->default('PENDING');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
