<?php

namespace App\Http\Controllers\Front;

use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\BookingServiceRepository;
use App\Http\Resources\Admin\BookingServiceResource;
use App\Http\Requests\Front\StoreBookingServiceRequest;

class BookingServiceController extends BaseController
{
    protected $repository;

    public function __construct(BookingServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(StoreBookingServiceRequest $request)
    {
        try {
            $booking = $this->repository->store($request);

            $booking = new BookingServiceResource($booking);

            return $this->sendResponse($booking, "Booking created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
