<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\ServiceRepository;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceCollection;
use App\Http\Resources\Admin\ServiceResource;

class ServiceController extends BaseController
{
    protected $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('services-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $services = $this->repository->index($request);

            $services = new ServiceCollection($services);

            return $this->sendResponse($services, "Service list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreServiceRequest $request)
    {
        if (!$request->user()->hasPermission("services-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $service = $this->repository->store($request);

            $service = new ServiceResource($service);

            return $this->sendResponse($service, "Service created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('services-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $service = $this->repository->show($id);
            if (!$service) {
                return $this->sendError("Service not found", 404);
            }

            $service = new ServiceResource($service);

            return $this->sendResponse($service, "Service single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateServiceRequest $request, $id)
    {
        if (!$request->user()->hasPermission('services-update')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $service = Service::find($id);

            if (!$service) {
                return $this->sendError("Service not found");
            }

            $service = $this->repository->update($request, $service);

            $service = new ServiceResource($service);

            return $this->sendResponse($service, "Service updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission('services-delete')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $service = Service::find($id);
            if (!$service) {
                return $this->sendError("Service not found");
            }

            $service = $this->repository->delete($service);

            return $this->sendResponse($service, "Service deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
