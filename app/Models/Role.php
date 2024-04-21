<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    use Userstamps;

    public $guarded = [];

    function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
