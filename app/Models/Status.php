<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "id");
    }

    public function booking_services(): HasMany
    {
        return $this->hasMany(BookingService::class, 'current_status_id', 'id');
    }
}
