<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ["id"];

    public $image_path = "images/application/photos";
    public $cv_path    = "images/application/cvs";

    public function address() : HasOne
    {
        return $this->hasOne(Address::class, "application_id", "id");
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, "application_id", "id");
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            if (File::exists(public_path("{$this->image_path}/{$value}"))) {

                return asset($this->image_path . '/' . $value);
            }
        }

        return asset('images/default.png');
    }

    public function getCvAttribute($value)
    {
        if ($value) {
            if (File::exists(public_path("{$this->cv_path}/{$value}"))) {

                return asset($this->cv_path . '/' . $value);
            }
        }

        return asset('images/default.png');
    }
}
