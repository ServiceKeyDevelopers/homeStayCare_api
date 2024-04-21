<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\BlogPostRepository;
use App\Http\Resources\Admin\BlogPostResource;
use App\Http\Resources\Admin\BlogPostCollection;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;

class BlogPostController extends BaseController
{
    protected $repository;

    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("blog-posts-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $blogPosts = $this->repository->index($request);

            $blogPosts = new BlogPostCollection($blogPosts);

            return $this->sendResponse($blogPosts, "Blog post list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreBlogPostRequest $request)
    {
        if (!$request->user()->hasPermission("blog-posts-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $blogPost = $this->repository->store($request);

            $blogPost = new BlogPostResource($blogPost);

            return $this->sendResponse($blogPost, "Blog post created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission('blog-posts-read')) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $blogPost = $this->repository->show($id);
            if (!$blogPost) {
                return $this->sendError("Blog post not found", 404);
            }

            $blogPost = new BlogPostResource($blogPost);

            return $this->sendResponse($blogPost, "Blog post single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateBlogPostRequest $request, $id)
    {
        if (!$request->user()->hasPermission("blog-posts-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $blogPost = BlogPost::find($id);

            if (!$blogPost) {
                return $this->sendError("Not found");
            }

            $blogPost = $this->repository->update($request, $blogPost);

            $blogPost = new BlogPostResource($blogPost);

            return $this->sendResponse($blogPost, "Blog post updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("blog-posts-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $blogPost = BlogPost::find($id);
            if (!$blogPost) {
                return $this->sendError("Blog post not found");
            }

            $blogPost = $this->repository->delete($blogPost);

            return $this->sendResponse($blogPost, "Blog post deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
