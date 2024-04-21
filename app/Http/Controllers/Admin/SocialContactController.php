<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SocialContact;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\SocialContactRepository;
use App\Http\Requests\Admin\StoreSocialContactRequest;
use App\Http\Requests\Admin\UpdateSocialContactRequest;
use App\Http\Resources\Admin\SocialContactCollection;
use App\Http\Resources\Admin\SocialContactResource;

class SocialContactController extends BaseController
{
    protected $repository;

    public function __construct(SocialContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission("social-contacts-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $socialContacts = $this->repository->index($request);

            $socialContacts = new SocialContactCollection($socialContacts);

            return $this->sendResponse($socialContacts, "Social contact list");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function store(StoreSocialContactRequest $request)
    {
        if (!$request->user()->hasPermission("social-contacts-create")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $socialContact = $this->repository->store($request);

            $socialContact = new SocialContactResource($socialContact);

            return $this->sendResponse($socialContact, "Social contact created successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->hasPermission("social-contacts-read")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $socialContact = $this->repository->show($id);
            if (!$socialContact) {
                return $this->sendError("socialContact not found", 404);
            }

            $socialContact = new SocialContactResource($socialContact);

            return $this->sendResponse($socialContact, "Social contact single view");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function update(UpdateSocialContactRequest $request, $id)
    {
        if (!$request->user()->hasPermission("social-contacts-update")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $socialContact = SocialContact::find($id);

            if (!$socialContact) {
                return $this->sendError("Not found");
            }

            $socialContact = $this->repository->update($request, $socialContact);

            $socialContact = new SocialContactResource($socialContact);

            return $this->sendResponse($socialContact, "Social contact updated successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError('Something went wrong');
        }
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasPermission("social-contact-delete")) {
            return $this->sendError(__("Common.unauthorized"));
        }

        try {
            $socialContact = SocialContact::find($id);
            if (!$socialContact) {
                return $this->sendError("Social contact not found");
            }

            $socialContact = $this->repository->delete($socialContact);

            return $this->sendResponse($socialContact, "Social contact deleted successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }
}
