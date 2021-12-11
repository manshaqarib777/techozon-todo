<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function show()
    {
        $user = auth()->user();

        return $this->respondWithSuccess($user);
    }

}
