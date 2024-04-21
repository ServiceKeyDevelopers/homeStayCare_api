<?php
namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\SocialContact;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class SocialContactRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $searchKey    = $request->input("search_key", null);
        $status       = $request->input("status", null);

        try {
            $socialContacts = SocialContact::orderBy('created_at', 'desc')
            ->when($status, fn ($query) => $query->where("status", $status))
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where("contact", "like", "%$searchKey%")
                ->orWhere("title", "like", "%$searchKey%")
                ->orWhere("type","like", "%$searchKey%");
            })
            ->paginate($paginateSize);

            return $socialContacts;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $title   = $request->input('title', null);
        $contact = $request->input('contact', null);
        $type    = $request->input('type', null);
        $status  = $request->input('status', StatusEnum::ACTIVE);

        try {
            DB::beginTransaction();

            $socialContact = new SocialContact();

            $socialContact->title   = $title;
            $socialContact->type    = $type;
            $socialContact->contact = $contact;
            $socialContact->status  = $status;
            $res = $socialContact->save();
            if ($res) {
                // Upload image
                if ($request->hasFile('icon')) {
                    $icon     = $request->file('icon');
                    $iconName = time() . '.' . 'webp';
                    $icon->move(public_path($socialContact->path), $iconName);
                    $socialContact->icon = $iconName;
                    $socialContact->save();
                }
            }
            DB::commit();

            return $socialContact;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $socialContact = SocialContact::find($id);

            return $socialContact;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, SocialContact $socialContact)
    {
        $title   = $request->input('title', null);
        $type    = $request->input('type', null);
        $contact = $request->input('contact', null);
        $status  = $request->input('status', StatusEnum::ACTIVE);

        try {
            DB::beginTransaction();

            $socialContact->title   = $title;
            $socialContact->type    = $type;
            $socialContact->contact = $contact;
            $socialContact->status  = $status;
            $res = $socialContact->save();
            if ($res) {
                if ($request->hasFile('icon')) {
                    $icon    = $request->file('icon');
                    $oldIcon = BH::getOldPath($socialContact->path, $socialContact->icon);

                    // Delete old image
                    if (File::exists($oldIcon)) {
                        File::delete($oldIcon);
                    }

                    $iconName = time() . '.' . 'webp';
                    $icon->move(public_path($socialContact->path), $iconName);
                    $socialContact->icon = $iconName;
                    $socialContact->save();
                }
            }
            DB::commit();

            return $socialContact;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(SocialContact $socialContact)
    {
        try {
            return $socialContact->forceDelete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
