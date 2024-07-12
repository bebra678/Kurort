<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegEmailRequest;
use App\Http\Requests\RegNumberRequest;
use App\Models\User;

class RegController extends Controller
{
    public function registerEmail(RegEmailRequest $request)
    {
        $data = $request->validated();
        User::firstOrCreate($data);
        $data->sendEmailVerificationNotification();
        return response()->json(['data' => $data, 'message' => 'Письмо для подтверждения email отправлено']);
    }

    public function registerNumber(RegNumberRequest $request)
    {
        $data = $request->validated();
        User::firstOrCreate($data);
        return response()->json($data);
    }
}
