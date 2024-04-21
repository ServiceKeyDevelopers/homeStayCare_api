<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\TagRepository;
use App\Http\Resources\Admin\TagResource;
use App\Http\Resources\Admin\TagCollection;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;

class TagController extends BaseController
{
    protected $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("tags-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $tags = $this->repository->index($request);

            $tags = new TagCollection($tags);

            return $this->sendResponse($tags, "Tag list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreTagRequest $request)
    {
        if (!$request->user()->hasPermission("tags-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $tag = $this->repository->store($request);

            $tag = new TagResource($tag);

            return $this->sendResponse($tag, "Tag created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission("tags-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $tag = $this->repository->show($id);
            if (!$tag) {
                return $this->sendError("Tag not found", 404);
            }

            $tag = new TagResource($tag);

            return $this->sendResponse($tag, "Tag single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateTagRequest $request, $id)
    {
        if (!$request->user()->hasPermission("tags-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $tag = Tag::find($id);

            if (!$tag) {
                return $this->sendError("Not found");
            }

            $tag = $this->repository->update($request, $tag);

            $tag = new TagResource($tag);

            return $this->sendResponse($tag, "Tag updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("tags-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $tag = Tag::find($id);
            if (!$tag) {
                return $this->sendError("Tag not found");
            }

            $tag = $this->repository->delete($tag);

            return $this->sendResponse($tag, "Tag deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
