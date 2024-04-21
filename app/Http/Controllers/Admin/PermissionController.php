<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\PermissionRepository;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Resources\Admin\PermissionCollection;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;

class PermissionController extends BaseController
{
    protected $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('permissions-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $permissions = $this->repository->index($request);

            $permissions = new PermissionCollection($permissions);

            return $this->sendResponse($permissions, 'Permission list');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StorePermissionRequest $request)
    {
        if (!$request->user()->hasPermission('permissions-create')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $permission = $this->repository->store($request);

            $permission = new PermissionResource($permission);

            return $this->sendResponse($permission, "Permission created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('permissions-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $permission = $this->repository->show($id);
            if (!$permission) {
                return $this->sendError("Permission not found", 404);
            }

            $permission = new PermissionResource($permission);

            return $this->sendResponse($permission, "Permission single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdatePermissionRequest $request, $id)
    {
        if (!$request->user()->hasPermission('permissions-update')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $permission = Permission::find($id);

            if (!$permission) {
                return $this->sendError('Permission not found', 404);
            }

            $permission = $this->repository->update($request, $permission);

            $permission = new PermissionResource($permission);

            return $this->sendResponse($permission, "Permission updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
