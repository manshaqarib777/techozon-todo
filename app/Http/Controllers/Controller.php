<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    //

    protected $statusCode = Response::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithSuccess($data = null)
    {
        if(is_null($data))
            return $this->respond(['success' => true]);

        return $this->respond(['success' => true,'data' => $data]);
    }

    /**
     * Respond in json with the given error.
     *
     * @param  mixed  $errors
     * @return \Illuminate\Http\Response
     */
    public function respondWithError($errors)
    {
        $errors = is_string($errors) ? [$errors] : $errors;

        return $this->respond(['success' => false,'errors' => $errors]);
    }
}
