<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $userID = $request['id'];
        $user = User::findOrFail($userID);

        if ($user->hasVerifiedEmail()) {
            //return response()->json(['message' => 'Email уже подтвержден.'], 400);
            return view('email_error');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        //return response()->json(['message' => 'Email успешно подтвержден.'], 200);
        return view('email_confirm');
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            //return response()->json(['message' => 'Email уже подтвержден.'], 400);
            return view('email_error');
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Письмо для подтверждения email отправлено'], 200);
    }
}
