<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BookingService;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\BookingServiceRepository;
use App\Http\Resources\Admin\BookingServiceResource;
use App\Http\Resources\Admin\BookingServiceCollection;
use App\Http\Requests\Admin\StoreBookingServiceRequest;
use App\Http\Requests\Admin\UpdateBookingServiceRequest;

class BookingServiceController extends BaseController
{
    protected $repository;

    public function __construct(BookingServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("booking-services-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $bookings = $this->repository->index($request);

            $bookings = new BookingServiceCollection($bookings);

            return $this->sendResponse($bookings, "Bookings list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreBookingServiceRequest $request)
    {
        if (!$request->user()->hasPermission("booking-services-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $booking = $this->repository->store($request);

            $booking = new BookingServiceResource($booking);

            return $this->sendResponse($booking, "Booking created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission("booking-services-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $booking = $this->repository->show($id);
            if (!$booking) {
                return $this->sendError("booking not found", 404);
            }

            $booking = new BookingServiceResource($booking);

            return $this->sendResponse($booking, "Booking single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateBookingServiceRequest $request, $id)
    {
        if (!$request->user()->hasPermission("booking-services-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $booking = BookingService::find($id);

            if (!$booking) {
                return $this->sendError("Not found");
            }

            $booking = $this->repository->update($request, $booking);

            $booking = new BookingServiceResource($booking);

            return $this->sendResponse($booking, "Booking updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("booking-services-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $booking = BookingService::find($id);
            if (!$booking) {
                return $this->sendError("booking not found");
            }

            $booking = $this->repository->delete($booking);

            return $this->sendResponse($booking, "booking deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
