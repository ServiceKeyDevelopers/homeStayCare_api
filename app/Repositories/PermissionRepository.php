<?php
namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $displayName  = $request->input('display_name', null);
        $name         = $request->input('name', null);

        try {

            $permissions = Permission::when($displayName, fn($query) => $query->where('display_name', 'like', "%$displayName%"))
            ->when($name, fn($query) => $query->where("name", "like", "%$name%"));

            return $permissions->orderBy('created_by', 'desc')->paginate($paginateSize);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }


    public function store($request)
    {
        $displayName = $request->input('display_name', null);
        $description = $request->input('description', null);
        $name        = Str::slug($displayName, '-');

        try {
            DB::beginTransaction();

            $permission = new Permission();

            $permission->name         = $name;
            $permission->display_name = $displayName;
            $permission->description  = $description;
            $permission->save();
            DB::commit();

            return $permission;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            return Permission::find($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Permission $permission)
    {
        $displayName = $request->input('display_name', null);
        $description = $request->input('description', null);
        $name        = Str::slug($displayName, '-');

        try {
            DB::beginTransaction();

            $permission->name         = $name;
            $permission->display_name = $displayName;
            $permission->description  = $description;
            $permission->save();
            DB::commit();

            return $permission;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }
}
