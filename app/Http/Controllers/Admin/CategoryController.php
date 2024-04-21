<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\CategoryRepository;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;

class CategoryController extends BaseController
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('categories-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $categories = $this->repository->index($request);

            $categories = new CategoryCollection($categories);

            return $this->sendResponse($categories, "Category list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        if (!$request->user()->hasPermission("categories-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $category = $this->repository->store($request);

            $category = new CategoryResource($category);

            return $this->sendResponse($category, "Category created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('categories-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $category = $this->repository->show($id);
            if (!$category) {
                return $this->sendError("Category not found", 404);
            }

            $category = new CategoryResource($category);

            return $this->sendResponse($category, "Category single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateCategoryRequest $request, $id)
    {
        if (!$request->user()->hasPermission('categories-update')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->sendError("Not found");
            }

            $category = $this->repository->update($request, $category);

            $category = new CategoryResource($category);

            return $this->sendResponse($category, "Category updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("categories-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->sendError("Category not found");
            }

            $category = $this->repository->delete($category);

            return $this->sendResponse($category, "Category deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
