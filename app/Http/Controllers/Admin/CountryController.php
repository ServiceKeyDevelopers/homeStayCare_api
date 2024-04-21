<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\CountryRepository;
use App\Http\Resources\Admin\CountryResource;
use App\Http\Resources\Admin\CountryCollection;
use App\Http\Requests\Admin\StoreCountryRequest;
use App\Http\Requests\Admin\UpdateCountryRequest;

class CountryController extends BaseController
{
    protected $repository;

    public function __construct(CountryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("countries-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $countries = $this->repository->index($request);

            $countries = new CountryCollection($countries);

            return $this->sendResponse($countries, "Country list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreCountryRequest $request)
    {
        if (!$request->user()->hasPermission("countries-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $country = $this->repository->store($request);

            $country = new CountryResource($country);

            return $this->sendResponse($country, "Country created successfully");
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
            $country = $this->repository->show($id);
            if (!$country) {
                return $this->sendError("Country not found", 404);
            }

            $country = new CountryResource($country);

            return $this->sendResponse($country, "Country single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateCountryRequest $request, $id)
    {
        if (!$request->user()->hasPermission("countries-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $country = Country::find($id);

            if (!$country) {
                return $this->sendError("Country not found");
            }

            $country = $this->repository->update($request, $country);

            $country = new CountryResource($country);

            return $this->sendResponse($country, "Country updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("countries-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $country = Country::find($id);
            if (!$country) {
                return $this->sendError("Country not found");
            }

            $country = $this->repository->delete($country);

            return $this->sendResponse($country, "Country deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
