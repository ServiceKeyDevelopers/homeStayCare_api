<?php

namespace App\Repositories;

use App\Models\Team;
use App\Enums\StatusEnum;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class TeamRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $status       = $request->input("status", null);

        try {
            $teams = Team::with(["createdBy:id,name"])->orderBy('created_at', 'desc')
            ->when($status, fn($query) => $query->where("status", $status))
            ->paginate($paginateSize);

            return $teams;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $name        = $request->input('name');
        $designation = $request->input('designation');
        $status      = $request->input('status', StatusEnum::ACTIVE);
        $description = $request->input('description');

        try {
            DB::beginTransaction();

            $team = new Team();

            $team->name        = $name;
            $team->designation = $designation;
            $team->status      = $status;
            $team->description = $description;
            $res = $team->save();
            if ($res) {
                // Upload image
                if ($request->hasFile('image')) {
                    $image     = $request->file('image');
                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($team->path), $imageName);
                    $team->image = $imageName;
                    $team->save();
                }
            }
            DB::commit();

            return $team;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id, $status = null)
    {
        try {
            return Team::with(["createdBy:id,name"])
            ->when($status, fn($query) => $query->where("status", $status))
            ->find($id);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, Team $team)
    {
        $name        = $request->input('name');
        $designation = $request->input('designation');
        $status      = $request->input('status', StatusEnum::ACTIVE);
        $description = $request->input('description');

        try {
            DB::beginTransaction();

            $team->name        = $name;
            $team->designation = $designation;
            $team->status      = $status;
            $team->description = $description;
            $res = $team->save();
            if ($res) {
                if ($request->hasFile('image')) {
                    $image    = $request->file('image');
                    $oldImage = BH::getOldPath($team->path, $team->image);

                    // Delete old image
                    if (File::exists($oldImage)) {
                        File::delete($oldImage);
                    }

                    $imageName = time() . '.' . 'webp';
                    $image->move(public_path($team->path), $imageName);
                    $team->image = $imageName;
                    $team->save();
                }
            }
            DB::commit();

            return $team;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(Team $team)
    {
        try {
            return $team->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
