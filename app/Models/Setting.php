<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $guarded = ["id"];

    public $path = 'images/settings';

    public function getLogoAttribute($value)
    {
        if ($value) {
            if (File::exists(public_path("{$this->path}/{$value}"))) {

                return asset($this->path . '/' . $value);
            }
        }

        return asset('images/default.png');
    }
}
