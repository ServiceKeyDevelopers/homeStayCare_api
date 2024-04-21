<?php

namespace App\Repositories;

use App\Models\City;
use App\Enums\StatusEnum;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $isPaginate   = $request->input('is_paginate', true);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $countryId    = $request->input("country_id", null);
        $name         = $request->input("name", null);
        $status       = $request->input("status", null);

        try {
            $cities = City::with(["country", "createdBy:id,name"])
                ->when($countryId, fn ($query) => $query->where("country_id", $countryId))
                ->when($name, fn ($query)   => $query->where("name", $name))
                ->when($status, fn ($query) => $query->where("status", $status))
                ->orderBy('created_at', 'desc');
            if ($isPaginate) {
                $cities = $cities->paginate($paginateSize);
            } else {
                $cities = $cities->get();
            }

            return $cities;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $city = new City();

            $city->country_id = $request->country_id;
            $city->name       = $request->name;
            $city->slug       = Str::slug($request->name);
            $city->status     = $request->status ?? StatusEnum::ACTIVE;
            $city->save();

            DB::commit();

            return $city;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $city = City::with(["country", "createdBy:id,name"])->find($id);

            return $city;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, City $city)
    {
        try {
            DB::beginTransaction();

            $city->country_id = $request->country_id;
            $city->name       = $request->name;
            $city->slug       = Str::slug($request->name);
            $city->status     = $request->status ?? StatusEnum::ACTIVE;
            $city->save();

            DB::commit();

            return $city;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(City $city)
    {
        try {
            return $city->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
