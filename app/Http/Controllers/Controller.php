<?php

namespace App\Http\Controllers;

abstract class Controller
{

    public function success($data, $message = "Fetched", $code = 200)
    {
        return $this->response($data, $message, $code, true);
    }

    public function failure($code = 500, $message = "Error")
    {
        return $this->response(null, $message, $code, false);
    }

    private function response($data, $message, $code, $success)
    {
        return response()->json([
            'data' => $data,
            'success' => $success,
            'code' => $code,
            'message' => $message
        ], $code);
    }
}
