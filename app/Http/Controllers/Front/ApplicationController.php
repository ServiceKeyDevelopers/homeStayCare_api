<?php

namespace App\Http\Controllers\Front;

use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\ApplicationRepository;
use App\Http\Requests\Front\StoreApplicationRequest;

class ApplicationController extends BaseController
{
    protected $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(StoreApplicationRequest $request)
    {
        try {
            $designation = $this->repository->store($request);

            if ($designation) {
                return $this->sendResponse(null, "Application send successfully");
            } else {
                return $this->sendError("Something went wrong");
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
