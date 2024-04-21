<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\SliderRepository;
use App\Http\Resources\Admin\SliderResource;
use App\Http\Resources\Admin\SliderCollection;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Http\Requests\Admin\UpdateSliderRequest;

class SliderController extends BaseController
{
    protected $repository;

    public function __construct(SliderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('sliders-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $sliders = $this->repository->index($request);

            $sliders = new SliderCollection($sliders);

            return $this->sendResponse($sliders, "Slider list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreSliderRequest $request)
    {
        if (!$request->user()->hasPermission("sliders-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $slider = $this->repository->store($request);

            $slider = new SliderResource($slider);

            return $this->sendResponse($slider, "Slider created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('sliders-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $slider = $this->repository->show($id);
            if (!$slider) {
                return $this->sendError("Slider not found", 404);
            }

            $slider = new SliderResource($slider);

            return $this->sendResponse($slider, "Slider single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateSliderRequest $request, $id)
    {
        if (!$request->user()->hasPermission('sliders-update')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $slider = Slider::find($id);

            if (!$slider) {
                return $this->sendError("Slider not found");
            }

            $slider = $this->repository->update($request, $slider);

            $slider = new SliderResource($slider);

            return $this->sendResponse($slider, "Slider updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }
}
