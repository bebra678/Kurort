<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegEmailRequest;
use App\Http\Requests\RegNumberRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class RegisterController extends Controller
{
    public function registerEmail(RegEmailRequest $request)
    {
        $data = $request->validated();
        User::firstOrCreate($data);
        return response()->json($data);
    }

    public function registerNumber(RegNumberRequest $request)
    {
        $data = $request->validated();
        User::firstOrCreate($data);
        return response()->json($data);
    }
}
