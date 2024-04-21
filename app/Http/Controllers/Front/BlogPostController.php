<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\BlogPostRepository;
use App\Http\Resources\Front\BlogPostResource;
use App\Http\Resources\Front\BlogPostCollection;

class BlogPostController extends BaseController
{
    protected $repository;

    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $blogPosts = $this->repository->index($request);

            $blogPosts = new BlogPostCollection($blogPosts);

            return $this->sendResponse($blogPosts, "Blog post list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show($id)
    {
        try {
            $blogPost = $this->repository->show($id, "active");
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
}
