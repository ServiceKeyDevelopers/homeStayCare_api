<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\CityRepository;
use App\Http\Resources\Front\CityCollection;

class CityController extends BaseController
{
    protected $repository;

    public function __construct(CityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $city = $this->repository->index($request);

            $city = new CityCollection($city);

            return $this->sendResponse($city, "City list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
