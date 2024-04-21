<?php

namespace App\Repositories;

use App\Models\Application;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ApplicationRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);

        $paginateSize = BH::checkPaginateSize($paginateSize);
        $status       = $request->input("status", null);
        $title        = $request->input("title", null);

        try {
            $services = Application::with(["createdBy:id,name"])->orderBy('created_at', 'desc')
                ->when($status, fn ($query) => $query->where("status", $status))
                ->when($title, fn ($query) => $query->where("title", "like", "%$title%"))
                ->paginate($paginateSize);

            return $services;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $applicationPayload = [
                "designation_id" => $request->designation_id,
                "first_name"     => $request->first_name,
                "middle_name"    => $request->middle_name,
                "last_name"      => $request->last_name,
                "email"          => $request->email,
                "phone_number"   => $request->phone_number,
                "date_of_birth"  => $request->date_of_birth,
                "reference_name" => $request->reference_name
            ];

            $addressPayload = [
                "country_id"       => $request->country_id,
                "city_id"          => $request->city_id,
                "post_code"        => $request->post_code,
                "nid_number"       => $request->nid_number,
                "passport_type"    => $request->passport_type,
                "passport_number"  => $request->passport_number,
                "address"          => $request->address,
                "address_line_1"   => $request->address_line_1,
                "address_line_2"   => $request->address_line_2,
                "kin_first_name"   => $request->kin_first_name,
                "kin_middle_name"  => $request->kin_middle_name,
                "kin_last_name"    => $request->kin_last_name,
                "kin_phone_number" => $request->kin_phone_number,
            ];

            $application = Application::create($applicationPayload);

            // Save schedule
            foreach ($request->schedules as $schedule) {
                $application->schedules()->create($schedule);
            }

            // Save Address
            $address = $application->address()->create($addressPayload);

            // Upload password
            if ($request->hasFile("passport")) {
                $passport     = $request->file("passport");
                $passportName = time() . "." . "webp";
                $passport->move(public_path($address->path), $passportName);
                $address->passport = $passportName;
                $address->save();
            }

            // Upload image
            if ($request->hasFile("image")) {
                $image     = $request->file("image");
                $imageName = time() . "." . "webp";
                $image->move(public_path($application->image_path), $imageName);
                $application->image = $imageName;
                $application->save();
            }

            // Upload cv
            if ($request->hasFile("cv")) {
                $cv       = $request->file("cv");
                $fileName = $cv->getClientOriginalName();
                $cvName   = time() . $fileName;
                $cv->move(public_path($application->cv_path), $cvName);
                $application->cv = $cvName;
                $application->save();
            }

            DB::commit();

            return $application;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            return Application::with(["address", "address.country", "address.city", "schedules", "createdBy:id,name"])
                ->find($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function delete(Application $application)
    {
        try {
            DB::beginTransaction();

            // Delete related schedules
            $application->schedules()->delete();

            // Delete the address and associated files
            if ($application->address) {
                // Delete passport file
                if ($application->address->passport) {
                    $passportPath = BH::getOldPath($application->address->path, $application->address->passport);

                    // Delete old image
                    if (File::exists($passportPath)) {
                        File::delete($passportPath);
                    }
                }

                // Delete image file
                if ($application->image) {
                    $imagePath = BH::getOldPath($application->image_path, $application->image);

                    // Delete old image
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }

                // Delete cv file
                if ($application->cv) {
                    $cvPath = BH::getOldPath($application->cv_path, $application->cv);

                    // Delete old image
                    if (File::exists($cvPath)) {
                        File::delete($cvPath);
                    }
                }

                // Delete the address
                $application->address->delete();
            }

            // Delete the application
            $res = $application->delete();

            DB::commit();

            return $res;

        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

}
