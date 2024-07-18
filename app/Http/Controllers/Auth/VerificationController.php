<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function sendVerificationCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user)
        {
            return response()->json(['success' => false, 'error' => 'Пользователь не найден']);
        }
        if($user->email_verified_at)
        {
            return response()->json(['success' => false, 'error' => 'Вы уже подтвердили свою почту']);
        }
        //$verificationCode = Str::random(6);
        $verificationCode = mt_rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->save();
        Mail::raw('Ваш код подтверждения: ' . $verificationCode, function ($message) use ($user) {
            $message->to($user->email)->subject('Код подтверждения');
        });
        return response()->json(['success' => true, 'message' => 'Код подтверждения отправлен на вашу почту']);
    }

    public function verifyCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user)
        {
            return response()->json(['success' => false, 'error' => 'Пользователь не найден']);
        }
        if($user->email_verified_at)
        {
            return response()->json(['success' => false, 'error' => 'Вы уже подтвердили свою почту']);
        }
        if ($user->verification_code === $request->code)
        {
            $user->verification_code = null;
            $user->email_verified_at = now();
            $user->save();
            Mail::raw( $user->name .', Вы успешно подтвердили свою почту!', function ($message) use ($user) {
                $message->to($user->email)->subject('Поздравляем с успешной регистрацией');
            });
            return response()->json(['success' => true, 'message' => 'Вы успешно подтвердили свою почту!']);
        }
        else
        {
            return response()->json(['success' => false, 'error' => 'Неверный код подтверждения']);
        }
    }

    //    public function verify(Request $request)
//    {
//        $userID = $request['id'];
//        $user = User::findOrFail($userID);
//
//        if ($user->hasVerifiedEmail()) {
//            //return response()->json(['message' => 'Email уже подтвержден.'], 400);
//            return view('email_error');
//        }
//
//        if ($user->markEmailAsVerified()) {
//            event(new Verified($user));
//        }
//
//        //return response()->json(['message' => 'Email успешно подтвержден.'], 200);
//        return view('email_confirm');
//    }
//
//    public function resend(Request $request)
//    {
//        $user = $request->user();
//
//        if ($user->hasVerifiedEmail()) {
//            //return response()->json(['message' => 'Email уже подтвержден.'], 400);
//            return view('email_error');
//        }
//
//        $user->sendEmailVerificationNotification();
//
//        return response()->json(['message' => 'Письмо для подтверждения email отправлено'], 200);
//    }
}
