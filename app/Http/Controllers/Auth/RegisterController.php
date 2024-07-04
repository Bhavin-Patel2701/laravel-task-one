<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'firstname' => ['required', 'alpha', 'min:2', 'max:255'],
            'lastname' => ['required', 'alpha', 'min:2', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'min:5', 'max:255', 'unique:users',
                'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,5}$/'
            ],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
            'mobile_number' => ['string', 'size:10', 'regex:/^[0-9]{10}$/']
        ], [
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'mobile_number.size' => 'The mobile number must be exactly 10 digits.',
            'mobile_number.regex' => 'The mobile number must contain only digits.'
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
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'mobile_number' => $data['mobile_number']
        ]);
    }
}
