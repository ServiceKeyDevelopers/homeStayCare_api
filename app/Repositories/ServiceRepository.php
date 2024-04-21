<?php

namespace App\Repositories;

use App\Models\Service;
use App\Enums\StatusEnum;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ServiceRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);

        $paginateSize = BH::checkPaginateSize($paginateSize);
        $status       = $request->input("status", null);
        $title        = $request->input("title", null);

        try {
            $services = Service::with(["createdBy:id,name"])->orderBy('created_at', 'desc')
            ->when($status, fn($query) => $query->where("status", $status))
            ->when($title, fn($query) => $query->where("title", "like", "%$title%"))
            ->paginate($paginateSize);

            return $services;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $title             = $request->input("title");
        $status            = $request->input("status", StatusEnum::ACTIVE);
        $shortDesignation1 = $request->input("short_description_1");
        $shortDesignation2 = $request->input("short_description_2");
        $description       = $request->input("description");

        try {
            DB::beginTransaction();

            $service = new Service();

            $service->title               = $title;
            $service->status              = $status;
            $service->short_description_1 = $shortDesignation1;
            $service->short_description_2 = $shortDesignation2;
            $service->description         = $description;
            $res = $service->save();
            if ($res) {
                // Upload banner image
                if ($request->hasFile("banner_image")) {
                    $bannerImage     = $request->file("banner_image");
                    $bannerImageName = time() . "bi." . "webp";
                    $bannerImage->move(public_path($service->path), $bannerImageName);
                    $service->banner_image = $bannerImageName;
                    $service->save();
                }

                // Upload first image
                if ($request->hasFile("first_image")) {
                    $firstImage     = $request->file("first_image");
                    $firstImageName = time() . "fi." . "webp";
                    $firstImage->move(public_path($service->path), $firstImageName);
                    $service->first_image = $firstImageName;
                    $service->save();
                }

                // Upload first image
                if ($request->hasFile("second_image")) {
                    $secondImage     = $request->file("second_image");
                    $secondImageName = time() . "si." . "webp";
                    $secondImage->move(public_path($service->path), $secondImageName);
                    $service->second_image = $secondImageName;
                    $service->save();
                }
            }

            DB::commit();

            return $service;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id, $status = null)
    {
        try {
            return Service::with(["createdBy:id,name"])
            ->when($status, fn($query) => $query->where("status", $status))
            ->find($id);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Service $service)
    {
        $title             = $request->input("title");
        $status            = $request->input("status", StatusEnum::ACTIVE);
        $shortDesignation1 = $request->input("short_description_1");
        $shortDesignation2 = $request->input("short_description_2");
        $description       = $request->input("description");

        try {
            DB::beginTransaction();

            $service->title               = $title;
            $service->status              = $status;
            $service->short_description_1 = $shortDesignation1;
            $service->short_description_2 = $shortDesignation2;
            $service->description         = $description;
            $res = $service->save();;
            if ($res) {
                // Update banner image
                if ($request->hasFile('banner_image')) {
                    $bannerImage    = $request->file('banner_image');
                    $bannerOldImage = BH::getOldPath($service->path, $service->banner_image);

                    // Delete old image
                    if (File::exists($bannerOldImage)) {
                        File::delete($bannerOldImage);
                    }

                    $bannerImageName = time() . 'bi.' . 'webp';
                    $bannerImage->move(public_path($service->path), $bannerImageName);
                    $service->banner_image = $bannerImageName;
                    $service->save();
                }

                // Update first image
                if ($request->hasFile("first_image")) {
                    $firstImage    = $request->file("first_image");
                    $firstOldImage = BH::getOldPath($service->path, $service->first_image);

                    // Delete old image
                    if (File::exists($firstOldImage)) {
                        File::delete($firstOldImage);
                    }

                    $firstImageName = time() . 'fi.' . 'webp';
                    $firstImage->move(public_path($service->path), $firstImageName);
                    $service->first_image = $firstImageName;
                    $service->save();
                }

                // Update second image
                if ($request->hasFile("second_image")) {
                    $secondImage    = $request->file("second_image");
                    $secondOldImage = BH::getOldPath($service->path, $service->second_image);

                    // Delete old image
                    if (File::exists($secondOldImage)) {
                        File::delete($secondOldImage);
                    }

                    $secondImageName = time() . 'si.' . 'webp';
                    $secondImage->move(public_path($service->path), $secondImageName);
                    $service->second_image = $secondImageName;
                    $service->save();
                }
            }

            DB::commit();

            return $service;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(Service $service)
    {
        try {
            return $service->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
