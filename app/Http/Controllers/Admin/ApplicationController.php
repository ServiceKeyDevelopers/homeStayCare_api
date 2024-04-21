<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\ApplicationRepository;
use App\Http\Resources\Admin\ApplicationResource;
use App\Http\Resources\Admin\ApplicationCollection;
use App\Models\Application;

class ApplicationController extends BaseController
{
    protected $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("applications-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $applications = $this->repository->index($request);

            $applications = new ApplicationCollection($applications);

            return $this->sendResponse($applications, "Application list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('applications-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $application = $this->repository->show($id);
            if (!$application) {
                return $this->sendError("Application not found", 404);
            }

            $application = new ApplicationResource($application);

            return $this->sendResponse($application, "Application single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("applications-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $application = Application::find($id);
            if (!$application) {
                return $this->sendError("Application not found");
            }

            $application = $this->repository->delete($application);

            return $this->sendResponse($application, "Application deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
