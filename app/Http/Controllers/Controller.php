<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function success($data = null, $message = null, $statusCode = 200) {
        $response = [
            "status" => true,
            "message" => $message,
        ];

        if ($data != null) $response += $data;

        return response()->json($response, $statusCode);
    }

    public function failed($message = null, $statusCode = 500, $errors = []) {
        $response = [
            "status" => false,
            "message" => $message,
            "errors" => $errors,
        ];

        return response()->json($response, $statusCode);
    }
}
