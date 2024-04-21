<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\TeamRepository;
use App\Http\Resources\Admin\TeamResource;
use App\Http\Resources\Admin\TeamCollection;
use App\Http\Requests\Admin\StoreTeamRequest;
use App\Http\Requests\Admin\UpdateTeamRequest;

class TeamController extends BaseController
{
    protected $repository;

    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('teams-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $teams = $this->repository->index($request);

            $teams = new TeamCollection($teams);

            return $this->sendResponse($teams, "Team list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreTeamRequest $request)
    {
        if (!$request->user()->hasPermission("teams-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $team = $this->repository->store($request);

            $team = new TeamResource($team);

            return $this->sendResponse($team, "Team created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('teams-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $team = $this->repository->show($id);
            if (!$team) {
                return $this->sendError("Team not found", 404);
            }

            $team = new TeamResource($team);

            return $this->sendResponse($team, "Team single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateTeamRequest $request, $id)
    {
        if (!$request->user()->hasPermission('teams-update')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $team = Team::find($id);

            if (!$team) {
                return $this->sendError("Team not found");
            }

            $team = $this->repository->update($request, $team);

            $team = new TeamResource($team);

            return $this->sendResponse($team, "Team updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission('teams-delete')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $team = Team::find($id);
            if (!$team) {
                return $this->sendError("Team not found");
            }

            $team = $this->repository->delete($team);

            return $this->sendResponse($team, "Team deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
