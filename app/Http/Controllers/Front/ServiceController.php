<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\ServiceRepository;
use App\Http\Resources\Front\ServiceResource;
use App\Http\Resources\Front\ServiceCollection;

class ServiceController extends BaseController
{
    protected $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $services = $this->repository->index($request);

            $services = new ServiceCollection($services);

            return $this->sendResponse($services, "Service list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show($id)
    {
        try {
            $service = $this->repository->show($id, "active");
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
}
