<?php

namespace App\Http\Controllers\Front;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\SocialContactRepository;
use App\Http\Resources\Front\SocialContactCollection;

class SocialContactController extends BaseController
{
    protected $repository;

    public function __construct(SocialContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $request = $request->merge(["status" => StatusEnum::ACTIVE]);

        try {
            $socialContacts = $this->repository->index($request);

            $socialContacts = new SocialContactCollection($socialContacts);

            return $this->sendResponse($socialContacts, "Social contact list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
