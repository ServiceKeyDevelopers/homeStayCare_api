<?php

namespace App\Repositories;

use App\Models\User;
use App\Classes\BaseHelper as BH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function registration($request)
    {
        $name        = $request->input('name');
        $phoneNumber = $request->input('phone_number');
        $exceptionmail       = $request->input('email');
        $password    = $request->input('password');
        $otp         = BH::getRandomCode();

        try {
            DB::beginTransaction();

            $user = new User();

            $user->name         = $name;
            $user->email        = $exceptionmail;
            $user->phone_number = $phoneNumber;
            $user->otp          = $otp;
            $user->password     = Hash::make($password);
            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            throw $exception;
        }
    }

    public function login($user)
    {
        try {
            // if (!$user->is_active) {
            //     return $this->sendError("User not active");
            // }

            $token =  $user->createToken('auth_token')->plainTextToken;

            $data = [
                'user'  => $user,
                'token' => $token
            ];

            return $data;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }

    public function logout($request)
    {
        try {
            return $request->user()->tokens()->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}
