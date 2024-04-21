<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Enums\StatusEnum;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $name         = $request->input("name", null);
        $status       = $request->input("status", null);

        try {
            $tags = Tag::with(["createdBy:id,name"])
                ->when($name, fn ($query) => $query->where("name", "like", "%$name%"))
                ->when($status, fn ($query) => $query->where("status", $status))
                ->orderBy('created_at', 'desc')
                ->paginate($paginateSize);

            return $tags;
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

            $tag = new Tag();

            $tag->name   = $name;
            $tag->slug   = $slug;
            $tag->status = $status;
            $tag->save();

            DB::commit();

            return $tag;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $tag = Tag::with(["createdBy:id,name"])->find($id);

            return $tag;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Tag $tag)
    {
        $name   = $request->input('name', null);
        $status = $request->input('status', StatusEnum::ACTIVE);
        $slug   = Str::slug($name);

        try {
            DB::beginTransaction();

            $tag->name    = $name;
            $tag->slug    = $slug;
            $tag->status  = $status;
            $tag->save();

            DB::commit();

            return $tag;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(Tag $tag)
    {
        try {
            return $tag->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
