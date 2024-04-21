<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = [];

    public $path = 'images/sliders';

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
