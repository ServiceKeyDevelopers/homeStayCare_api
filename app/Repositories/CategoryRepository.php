<?php

namespace App\Repositories;

use App\Models\Category;
use App\Enums\StatusEnum;
use Illuminate\Support\Str;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CategoryRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $status       = $request->input("status", null);
        $name         = $request->input("name", null);

        try {
            $categories = Category::with(["createdBy:id,name"])
            ->when($status, fn ($query) => $query->where("status", $status))
            ->when($name, fn ($query)   => $query->where("name", $name))
            ->orderBy('created_at', 'desc')
            ->paginate($paginateSize);

            return $categories;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $name   = $request->input('name', null);
        $isTop  = $request->input('is_top', 1);
        $status = $request->input('status', StatusEnum::ACTIVE);
        $slug   = Str::slug($name);

        try {
            DB::beginTransaction();

            $category = new Category();

            $category->name   = $name;
            $category->slug   = $slug;
            $category->status = $status;
            $category->is_top = $isTop;
            $res = $category->save();
            if ($res) {
                // Upload image
                if ($request->hasFile('image')) {
                    $image     = $request->file('image');
                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($category->path), $imageName);
                    $category->image = $imageName;
                    $category->save();
                }
            }
            DB::commit();

            return $category;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with(["createdBy:id,name"])->find($id);

            return $category;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Category $category)
    {
        $name   = $request->input('name', null);
        $isTop  = $request->input('is_top', 1);
        $status = $request->input('status', StatusEnum::ACTIVE);
        $slug   = Str::slug($name);

        try {
            DB::beginTransaction();

            $category->name    = $name;
            $category->slug    = $slug;
            $category->is_top  = $isTop;
            $category->status  = $status;
            $res = $category->save();
            if ($res) {
                if ($request->hasFile("image")) {
                    $file         = $request->file("image");
                    $oldImagePath = BH::getOldPath($category->path, $category->image);

                    // Delete old image
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }

                    $imageName = time() . "." . "webp";
                    $file->move(public_path($category->path), $imageName);
                    $category->image = $imageName;
                    $category->save();
                }
            }
            DB::commit();

            return $category;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(Category $category)
    {
        try {
            return $category->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
