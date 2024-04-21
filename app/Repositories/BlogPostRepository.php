<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Enums\StatusEnum;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogPostRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $title        = $request->input("title", null);
        $categoryId   = $request->input("category_id", null);

        try {
            $socialContacts = BlogPost::with(["category:id,name", "tags:id,name", "createdBy:id,name"])
                ->orderBy('created_at', 'desc')
                ->when($title, fn ($query) => $query->where("title", "like", "%$title%"))
                ->when($categoryId, fn ($query) => $query->where("category_id", $categoryId))
                ->paginate($paginateSize);

            return $socialContacts;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $title           = $request->input('title', null);
        $categoryId      = $request->input('category_id', null);
        $tagIds          = $request->input('tag_ids', []);
        $status          = $request->input('status', StatusEnum::ACTIVE);
        $metaTitle       = $request->input('meta_title', null);
        $metaTag         = $request->input('meta_tag', null);
        $metaDescription = $request->input('meta_description', null);
        $description     = $request->input('description');
        $dom = new \DomDocument();

        $dom->loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');
        $baseUrl = config("app.url");
        foreach ($images as $k => $img) {
            $image_64 = $img->getAttribute('src');
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

            $replace = substr($image_64, 0, strpos($image_64, ',')+1);

            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);

            $imageName = Str::random(10).'.'.$extension;

            $image_name = "/upload/".$imageName;
            $path = public_path().$image_name;

            file_put_contents($path, base64_decode($image));
            $img->removeAttribute('src');
            $img->setAttribute('src', "$baseUrl$image_name");

        }

        $description = $dom->saveHTML();


        try {
            DB::beginTransaction();

            $blogPost = new BlogPost();

            $blogPost->title            = $title;
            $blogPost->category_id      = $categoryId;
            $blogPost->status           = $status;
            $blogPost->description      = $description;
            $blogPost->meta_title       = $metaTitle;
            $blogPost->meta_tag         = $metaTag;
            $blogPost->meta_description = $metaDescription;
            $res = $blogPost->save();
            if ($res) {
                // Sync with tags
                $blogPost->tags()->sync($tagIds);

                // Upload image
                if ($request->hasFile('image')) {
                    $image     = $request->file('image');
                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($blogPost->path), $imageName);
                    $blogPost->image = $imageName;
                    $blogPost->save();
                }
            }
            DB::commit();

            return $blogPost;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $blogPost = BlogPost::with(["category:id,name", "tags:id,name", "createdBy:id,name"])->find($id);

            return $blogPost;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, BlogPost $blogPost)
    {
        $title           = $request->input('title', null);
        $categoryId      = $request->input('category_id', null);
        $tagIds          = $request->input('tag_ids', []);
        $status          = $request->input('status', StatusEnum::ACTIVE);
        $metaTitle       = $request->input('meta_title', null);
        $metaTag         = $request->input('meta_tag', null);
        $metaDescription = $request->input('meta_description', null);
        $description     = $request->input('description');
        $dom = new \DomDocument();

        $dom->loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');
        $baseUrl = config("app.url");
        foreach ($images as $k => $img) {
            $image_64 = $img->getAttribute('src');
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);

            $imageName = Str::random(10) . '.' . $extension;

            $image_name = "/upload/" . $imageName;
            $path = public_path() . $image_name;

            file_put_contents($path, base64_decode($image));
            $img->removeAttribute('src');
            $img->setAttribute('src', "$baseUrl$image_name");
        }

        $description = $dom->saveHTML();

        try {
            DB::beginTransaction();

            $blogPost->title            = $title;
            $blogPost->category_id      = $categoryId;
            $blogPost->status           = $status;
            $blogPost->description      = $description;
            $blogPost->meta_title       = $metaTitle;
            $blogPost->meta_tag         = $metaTag;
            $blogPost->meta_description = $metaDescription;
            $res = $blogPost->save();
            if ($res) {
                // Sync with tags
                $blogPost->tags()->detach();
                $blogPost->tags()->sync($tagIds);

                if ($request->hasFile('image')) {
                    $image    = $request->file('image');
                    $oldImage = BH::getOldPath($blogPost->path, $blogPost->image);

                    // Delete old image
                    if (File::exists($oldImage)) {
                        File::delete($oldImage);
                    }

                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($blogPost->path), $imageName);
                    $blogPost->image = $imageName;
                    $blogPost->save();
                }
            }
            DB::commit();

            return $blogPost;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(BlogPost $blogPost)
    {
        try {
            return $blogPost->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
