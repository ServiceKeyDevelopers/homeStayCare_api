<?php

namespace App\Http\Controllers\Admin;

use App\Models\Designation;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\DesignationRepository;
use App\Http\Resources\Admin\DesignationResource;
use App\Http\Resources\Admin\DesignationCollection;
use App\Http\Requests\Admin\StoreDesignationRequest;
use App\Http\Requests\Admin\UpdateDesignationRequest;

class DesignationController extends BaseController
{
    protected $repository;

    public function __construct(DesignationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("designations-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $designations = $this->repository->index($request);

            $designations = new DesignationCollection($designations);

            return $this->sendResponse($designations, "Designation list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreDesignationRequest $request)
    {
        if (!$request->user()->hasPermission("designations-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $designation = $this->repository->store($request);

            $designation = new DesignationResource($designation);

            return $this->sendResponse($designation, "Designation created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission("designations-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $designation = $this->repository->show($id);
            if (!$designation) {
                return $this->sendError("Designation not found", 404);
            }

            $designation = new DesignationResource($designation);

            return $this->sendResponse($designation, "Designation single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateDesignationRequest $request, $id)
    {
        if (!$request->user()->hasPermission("designations-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $designation = Designation::find($id);

            if (!$designation) {
                return $this->sendError("Designation not found");
            }

            $designation = $this->repository->update($request, $designation);

            $designation = new DesignationResource($designation);

            return $this->sendResponse($designation, "Designation updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("designations-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $designation = Designation::find($id);
            if (!$designation) {
                return $this->sendError("Designation not found");
            }

            $designation = $this->repository->delete($designation);

            return $this->sendResponse($designation, "Designation deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
