<?php
namespace App\Repositories;

use App\Models\Slider;
use App\Enums\StatusEnum;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class SliderRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $status       = $request->input("status", null);

        try {
            $sliders = Slider::orderBy('created_at', 'desc')
            ->when($status, fn($query) => $query->where("status", $status))
            ->paginate($paginateSize);

            return $sliders;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $title       = $request->input('title');
        $link        = $request->input('link', null);
        $description = $request->input('description', null);
        $status      = $request->input('status', StatusEnum::ACTIVE);

        try {
            DB::beginTransaction();

            $slider = new Slider();

            $slider->title       = $title;
            $slider->status      = $status;
            $slider->link        = $link;
            $slider->description = $description;
            $res = $slider->save();
            if ($res) {
                // Upload image
                if ($request->hasFile('image')) {
                    $image     = $request->file('image');
                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($slider->path), $imageName);
                    $slider->image = $imageName;
                    $slider->save();
                }
            }
            DB::commit();

            return $slider;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $slider = Slider::find($id);

            return $slider;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Slider $slider)
    {
        $title       = $request->input('title');
        $link        = $request->input('link', null);
        $description = $request->input('description', null);
        $active      = $request->input('status', StatusEnum::ACTIVE);

        try {
            DB::beginTransaction();

            $slider->title       = $title;
            $slider->status      = $active;
            $slider->link        = $link;
            $slider->description = $description;
            $res = $slider->save();
            if ($res) {
                if ($request->hasFile('image')) {
                    $image    = $request->file('image');
                    $oldImage = BH::getOldPath($slider->path, $slider->image);

                    // Delete old image
                    if (File::exists($oldImage)) {
                        File::delete($oldImage);
                    }

                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($slider->path), $imageName);
                    $slider->image = $imageName;
                    $slider->save();
                }
            }
            DB::commit();

            return $slider;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }
}
