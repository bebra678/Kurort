<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function create(SupportRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Support::firstOrCreate($data);
        return response()->json($data);
    }
}
