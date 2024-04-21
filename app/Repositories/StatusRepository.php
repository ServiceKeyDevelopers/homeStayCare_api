<?php

namespace App\Repositories;

use Exception;
use App\Models\Status;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;

class StatusRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);

        try {
            $statuses = Status::orderBy('created_at', 'desc')->paginate($paginateSize);

            return $statuses;
        } catch (Exception $exception) {

            throw $exception;
        }
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $status = new Status();

            $status->name = $request->name;
            $status->slug = $request->name;
            $status->bg_color = $request->bg_color;
            $status->text_color = $request->text_color;
            $status->status = $request->status;
            $status->save();

            DB::commit();

            return $status;
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $status = Status::with(["createdBy:id,name", "updatedBy:id,name"])->find($id);

            return $status;
        } catch (Exception $exception) {

            throw $exception;
        }
    }

    function update($request, $id)
    {
        try {
            DB::beginTransaction();

            $status = Status::findOrFail($id);

            $status->name       = $request->name;
            $status->slug       =  Str::slug($request->name);
            $status->text_color = $request->text_color;
            $status->bg_color   = $request->bg_color;
            $status->status     = $request->status;
            $status->save();

            DB::commit();

            return $status;
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }
    }

    public function delete($id)
    {
        try {
            $status = Status::findOrFail($id);

            return $status->delete();
        } catch (Exception $exception) {

            throw $exception;
        }
    }
}
