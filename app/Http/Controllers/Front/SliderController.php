<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Enums\StatusEnum;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\SliderRepository;
use App\Http\Resources\Front\SliderCollection;

class SliderController extends BaseController
{
    protected $repository;

    public function __construct(SliderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $sliders = $this->repository->index($request);

            $sliders = new SliderCollection($sliders);

            return $this->sendResponse($sliders, "Slider list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
