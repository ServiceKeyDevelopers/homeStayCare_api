<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Designation extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $guarded = ["id"];

    public function createdBy() : BelongsTo
    {
       return $this->belongsTo(User::class, "created_by", 'id');
    }
}