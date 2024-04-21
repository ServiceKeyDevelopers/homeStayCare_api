<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\DesignationRepository;
use App\Http\Resources\Front\DesignationCollection;

class DesignationController extends BaseController
{
    protected $repository;

    public function __construct(DesignationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $designations = $this->repository->index($request);

            $designations = new DesignationCollection($designations);

            return $this->sendResponse($designations, "Designation list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
