<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\BookingService;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingServiceRepository
{
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size', null);
        $paginateSize = BH::checkPaginateSize($paginateSize);
        $name         = $request->input("name", null);
        $status       = $request->input("status", null);

        try {
            $bookings = BookingService::with(["createdBy:id,name"])
                ->when($name, fn($query) => $query->where("name", "like", "%$name%"))
                ->when($status, fn($query) => $query->where("status", $status))
                ->orderBy('created_at', 'desc')
                ->paginate($paginateSize);

            return $bookings;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function store($request)
    {
        $name       = $request->input('name', null);
        $email      = $request->input('email', null);
        $Post_code  = $request->input('Post_code', null);
        $Service_id = $request->input('Service_id', null);
        $status     = $request->input('status', StatusEnum::ACTIVE);

        try {
            DB::beginTransaction();
            $booking = new BookingService();

            $booking->name       = $name;
            $booking->email      = $email;
            $booking->Post_code  = $Post_code;
            $booking->Service_id = $Service_id;
            $booking->status     = $status;
            $booking->save();

            DB::commit();

            return $booking;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function show($id)
    {
        try {
            $booking = BookingService::with(["createdBy:id,name"])->find($id);

            return $booking;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, BookingService $booking)
    {
        $name       = $request->input('name', null);
        $email      = $request->input('email', null);
        $Post_code  = $request->input('Post_code', null);
        $Service_id = $request->input('Service_id', null);
        $status     = $request->input('status', StatusEnum::ACTIVE);

        try {
            DB::beginTransaction();

            $booking->name       = $name;
            $booking->email      = $email;
            $booking->Post_code  = $Post_code;
            $booking->Service_id = $Service_id;
            $booking->status     = $status;
            $booking->save();

            DB::commit();

            return $booking;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function delete(BookingService $booking)
    {
        try {
            return $booking->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
