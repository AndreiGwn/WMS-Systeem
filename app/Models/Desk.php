<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\DeskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desk extends Model
{
    /** @use HasFactory<DeskFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'zone',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function hasOpenTicket(): bool
    {
        return $this->tickets()->whereIn('status', ['open', 'in_progress'])->exists();
    }

    public function activeBookingOn(string|Carbon $date): ?Booking
    {
        $dateStr = $date instanceof Carbon ? $date->toDateString() : Carbon::parse($date)->toDateString();

        return $this->bookings()
            ->whereDate('booking_date', $dateStr)
            ->where('status', 'active')
            ->with('user')
            ->first();
    }

    public function isBookedOn(string|Carbon $date): bool
    {
        return $this->activeBookingOn($date) !== null;
    }

    public function statusOn(string|Carbon $date): string
    {
        if ($this->hasOpenTicket()) {
            return 'defect'; // Amber
        }

        if ($this->isBookedOn($date)) {
            return 'booked'; // Rose
        }

        return 'free'; // Emerald
    }
}
