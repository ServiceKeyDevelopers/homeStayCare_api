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
            $bookings = BookingService::with(["service", "currentStatus", "createdBy:id,name"])
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
        try {
            DB::beginTransaction();
            $booking = new BookingService();

            $booking->name              = $request->name;
            $booking->current_status_id = $request->current_status_id ?? 1;
            $booking->email             = $request->email;
            $booking->post_code         = $request->post_code;
            $booking->service_id        = $request->service_id;
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
            $booking = BookingService::with(["service", "currentStatus", "createdBy:id,name"])->find($id);
            return $booking;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function update($request, BookingService $booking)
    {
        try {
            DB::beginTransaction();

            $booking->name              = $request->name;
            $booking->email             = $request->email;
            $booking->post_code         = $request->post_code;
            $booking->service_id        = $request->service_id;
            $booking->current_status_id = $request->current_status_id;
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
