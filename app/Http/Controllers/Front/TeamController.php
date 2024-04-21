<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\TeamRepository;
use App\Http\Resources\Front\TeamResource;
use App\Http\Resources\Front\TeamCollection;

class TeamController extends BaseController
{
    protected $repository;

    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $teams = $this->repository->index($request);

            $teams = new TeamCollection($teams);

            return $this->sendResponse($teams, "Team list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show($id)
    {
        try {
            $slider = $this->repository->show($id, "active");
            if (!$slider) {
                return $this->sendError("Team not found", 404);
            }

            $slider = new TeamResource($slider);

            return $this->sendResponse($slider, "Team single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
