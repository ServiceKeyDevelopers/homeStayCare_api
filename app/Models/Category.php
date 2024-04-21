<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, Userstamps;

    public $path = "images/categories";

    public function createdBy() : BelongsTo
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
