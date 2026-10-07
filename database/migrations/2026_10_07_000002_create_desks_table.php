<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // bijv. "Werkplek A1"
            $table->string('zone'); // bijv. "Stiltezone", "IT-Vleugel"
            $table->json('features')->nullable(); // JSON: zit-sta, dual-screen, usb-c dock
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desks');
    }
};
