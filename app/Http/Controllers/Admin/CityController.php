<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\CityRepository;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\CityCollection;
use App\Http\Requests\Admin\StoreCityRequest;
use App\Http\Requests\Admin\UpdateCityRequest;

class CityController extends BaseController
{
    protected $repository;

    public function __construct(CityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("cities-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $cities = $this->repository->index($request);

            $cities = new CityCollection($cities);

            return $this->sendResponse($cities, "City list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreCityRequest $request)
    {
        if (!$request->user()->hasPermission("cities-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $city = $this->repository->store($request);

            $city = new CityResource($city);

            return $this->sendResponse($city, "City created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission("countries-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $city = $this->repository->show($id);
            if (!$city) {
                return $this->sendError("City not found", 404);
            }

            $city = new CityResource($city);

            return $this->sendResponse($city, "City single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateCityRequest $request, $id)
    {
        if (!$request->user()->hasPermission("countries-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $city = City::find($id);

            if (!$city) {
                return $this->sendError("City not found");
            }

            $city = $this->repository->update($request, $city);

            $city = new CityResource($city);

            return $this->sendResponse($city, "City updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("cities-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $city = City::find($id);
            if (!$city) {
                return $this->sendError("City not found");
            }

            $city = $this->repository->delete($city);

            return $this->sendResponse($city, "City deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
