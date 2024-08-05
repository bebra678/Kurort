<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:30', 'min:2', 'regex:/^[А-Я][\p{Cyrillic}-]+$/u'],
            'email' => ['nullable', 'string', 'email', 'min:10','max:100', Rule::unique('users')],
            'password' => ['required', 'string', 'max:100', 'min:6'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::firstOrCreate($data);

        //$user->sendEmailVerificationNotification();

        $verificationCode = mt_rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->save();
        Mail::raw('Ваш код подтверждения: ' . $verificationCode, function ($message) use ($user) {
            $message->to($user->email)->subject('Код подтверждения');
        });

        return $user;
    }

    public function register(UserRequest $request)
    {
        $request->validated();
        $data = User::where('email', $request->email)->first();
        if($data)
        {
            if($data->hasVerifiedEmail())
            {
                return response()->json(['success' => false, 'errors' => 'Данная почта занята']);
            }
            return response()->json(['success' => false, 'errors' => 'Вы не подтвердили почту']);
        }
        event(new Registered($user = $this->create($request->all())));
        return response()->json(['success' => true, 'message' => 'Код подтверждения отправлен на вашу почту']);
    }
}
