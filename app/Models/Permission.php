<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    use Userstamps;

    public $guarded = [];
}
