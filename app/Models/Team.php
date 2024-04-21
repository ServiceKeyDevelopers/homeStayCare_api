<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $guarded = ["id"];

    public $path = 'images/teams';

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            if (File::exists(public_path("{$this->path}/{$value}"))) {

                return asset($this->path . '/' . $value);
            }
        }

        return asset('images/default.png');
    }
}
