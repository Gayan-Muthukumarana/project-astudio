<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $code)
    {
        return response()->json([
            'success' => true,
            'statusCode' => $code,
            'message' => $data['responseMessage'],
            $data['responseName'] => $data['data'],
        ], $code);
    }

    /**
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($data, $code)
    {
        return response()->json([
            'success' => false,
            'statusCode' => $code,
            'message' => $data['responseMessage'],
            $data['responseName'] => $data['data'],
        ], $code);
    }
}
