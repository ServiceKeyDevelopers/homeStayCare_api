<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;
use App\Repositories\StatusRepository;
use App\Http\Resources\Admin\StatusResource;
use App\Http\Resources\Admin\StatusCollection;
use App\Http\Requests\Admin\StoreStatusRequest;
use App\Http\Requests\Admin\UpdateStatusRequest;

class StatusController extends BaseController
{
    protected $repository;
    public function __construct(StatusRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('statuses-read')) {
            return $this->sendError(__("common.unauthorized"));
        }

        try {
            $statuses = $this->repository->index($request);

            $statuses = new StatusCollection($statuses);

            return $this->sendResponse($statuses, 'Status list');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }

    public function store(StoreStatusRequest $request)
    {
        if (!$request->user()->hasPermission('statuses-create')) {
            return $this->sendError(__("common.unauthorized"));
        }

        try {
            $status = $this->repository->store($request);

            $status = new StatusResource($status);

            return $this->sendResponse($status, 'Status created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }

    function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('statuses-read')) {
            return $this->sendError(__("common.unauthorized"));
        }

        try {
            $status = $this->repository->show($id);

            $status = new StatusResource($status);

            return $this->sendResponse($status, "Status single view");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }

    public function update(UpdateStatusRequest $request, $id)
    {
        if (!$request->user()->hasPermission('statuses-update')) {
            return $this->sendError(__("common.unauthorized"));
        }

        try {
            $status = $this->repository->update($request, $id);

            $status = new StatusResource($status);

            return $this->sendResponse($status, 'Status updated successfully');
        }catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->hasPermission('statuses-delete')) {
            return $this->sendError(__("common.unauthorized"));
        }

        try {
            $status = $this->repository->delete($id);

            return $this->sendResponse($status, 'Status deleted successfully');
        }catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }
}
