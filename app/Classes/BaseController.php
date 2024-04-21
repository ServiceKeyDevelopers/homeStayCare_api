<?php

namespace App\Classes;

use App\Classes\BaseHelper as BH;
use Illuminate\Routing\Controller;
class BaseController extends Controller
{
    function sendResponse($result, $message, $code = 200) {
        return BH::sendResponse($result, $message, $code);
    }

    function sendError($message, $code = 400) {
        return BH::sendError($message, $code);
    }
}
