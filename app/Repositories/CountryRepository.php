<?php

namespace App\Repositories;

use App\Models\Country;
use App\Enums\StatusEnum;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountryRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $name         = $request->input("name", null);
        $status       = $request->input("status", null);

        try {
            $countries = Country::with(["createdBy:id,name"])
                ->when($name, fn ($query)   => $query->where("name", $name))
                ->when($status, fn ($query) => $query->where("status", $status))
                ->orderBy('created_at', 'desc')
                ->paginate($paginateSize);

            return $countries;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $name   = $request->input('name', null);
        $status = $request->input('status', StatusEnum::ACTIVE);
        $slug   = Str::slug($name);

        try {
            DB::beginTransaction();

            $country = new Country();

            $country->name   = $name;
            $country->slug   = $slug;
            $country->status = $status;
            $country->save();

            DB::commit();

            return $country;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $country = Country::with(["createdBy:id,name"])->find($id);

            return $country;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Country $country)
    {
        $name   = $request->input('name', null);
        $status = $request->input('status', StatusEnum::ACTIVE);
        $slug   = Str::slug($name);

        try {
            DB::beginTransaction();

            $country->name   = $name;
            $country->slug   = $slug;
            $country->status = $status;
            $country->save();

            DB::commit();

            return $country;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(Country $country)
    {
        try {
            return $country->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
