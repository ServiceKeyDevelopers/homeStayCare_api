<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\Designation;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DesignationRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $name         = $request->input("name", null);
        $status       = $request->input("status", null);

        try {
            $designations = Designation::with(["createdBy:id,name"])
                ->when($name, fn ($query)   => $query->where("name", $name))
                ->when($status, fn ($query) => $query->where("status", $status))
                ->orderBy('created_at', 'desc')
                ->paginate($paginateSize);

            return $designations;
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

            $designation = new Designation();

            $designation->name   = $name;
            $designation->slug   = $slug;
            $designation->status = $status;
            $designation->save();

            DB::commit();

            return $designation;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $designation = Designation::with(["createdBy:id,name"])->find($id);

            return $designation;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Designation $designation)
    {
        $name   = $request->input('name', null);
        $status = $request->input('status', StatusEnum::ACTIVE);
        $slug   = Str::slug($name);

        try {
            DB::beginTransaction();

            $designation->name   = $name;
            $designation->slug   = $slug;
            $designation->status = $status;
            $designation->save();

            DB::commit();

            return $designation;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(Designation $designation)
    {
        try {
            return $designation->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
