<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\CountryRepository;
use App\Http\Resources\Front\CountryCollection;

class CountryController extends BaseController
{
    protected $repository;

    public function __construct(CountryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $country = $this->repository->index($request);

            $country = new CountryCollection($country);

            return $this->sendResponse($country, "Country list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
