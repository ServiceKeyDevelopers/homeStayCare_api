<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ["id"];

    public $path = "images/passport";

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class, "country_id", "id");
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, "city_id", "id");
    }

    public function getPassportAttribute($value)
    {
        if ($value) {
            if (File::exists(public_path("{$this->path}/{$value}"))) {

                return asset($this->path . '/' . $value);
            }
        }

        return asset('images/default.png');
    }
}
