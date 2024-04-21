<?php

namespace App\Classes;

class BaseHelper
{
    public static function sendResponse($result, $message, $code = 200)
    {
        $response = [
            "success" => true,
            "message" => $message,
            "result"  => $result
        ];

        return response()->json($response, $code);
    }

    public static function sendError($message, $code = 400)
    {
        $response = [
            "success" => false,
            "message" => $message,
        ];

        return response()->json($response, $code);
    }

    public static function checkPaginateSize($paginate = null)
    {
        $maxPaginate     = config('crud.paginate.max');
        $defaultPaginate = config('crud.paginate.default');
        $paginate        = $paginate ?? $defaultPaginate;
        $paginate        = $paginate > $maxPaginate ? $maxPaginate : $paginate;

        return $paginate;
    }

    public static function getOldPath($path, $imageName)
    {
        // Use pathinfo to extract the filename
        $pathInfo = pathinfo($imageName);

        // Get the filename from pathinfo
        $filename = $pathInfo["basename"];

        return public_path($path . "/" . $filename);
    }

    public static function formatPhoneNumber($phoneNumber)
    {
        if (str_starts_with($phoneNumber, '0')) {
            return $phoneNumber;
        } elseif (str_starts_with($phoneNumber, '1')) {
            return $phoneNumber = '0' . $phoneNumber;
        } elseif (str_starts_with($phoneNumber, '80')) {
            return $phoneNumber = substr($phoneNumber, 1);
        } elseif (str_starts_with($phoneNumber, '88')) {
            return $phoneNumber = substr($phoneNumber, 2);
        } elseif (str_starts_with($phoneNumber, '+88')) {
            return $phoneNumber = substr($phoneNumber, 3);
        } else {
            return $phoneNumber;
        }
    }

    static public function getRandomCode()
    {
        $otpCode = rand(1111, 9999);

        return $otpCode;
    }
}
