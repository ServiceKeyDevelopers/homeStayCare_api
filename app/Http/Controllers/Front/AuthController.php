<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Front\LoginRequest;
use App\Http\Resources\Front\UserResource;
use App\Http\Requests\Front\RegistrationRequest;

class AuthController extends BaseController
{
    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    function registration(RegistrationRequest $request)
    {
        try {
            $user = $this->repository->registration($request);

            $user = new UserResource($user);

            return $this->sendResponse($user, "User registration successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    function login(LoginRequest $request)
    {
        $email    = $request->input('email', null);
        $password = $request->input('password', null);

        try {
            $user = User::where('email', $email)->first();

            if ($user) {
                if (Hash::check($password, $user->password)) {

                    $data = $this->repository->login($user);

                    return $this->sendResponse($data, "Login successfully");
                } else {
                    return $this->sendError("User credential dosen't match",);
                }
            } else {
                return $this->sendError("User not found", 404);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->sendError("Something went wrong");
        }
    }

    public function logout(Request $request)
    {
        $this->repository->logout($request);

        return $this->sendResponse(null, "User logout successfully");
    }
}
