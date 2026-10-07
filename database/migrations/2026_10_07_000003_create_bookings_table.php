<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('desk_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->string('work_hours_notes')->nullable(); // bijv. "08:30 - 16:30"
            $table->string('status')->default('active'); // 'active' of 'cancelled'
            $table->timestamps();

            $table->index(['desk_id', 'booking_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
