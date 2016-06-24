<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserRankEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;
    use ThrottlesLogins;
    
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }


    /**
     * An override of Laravel's login method to allow users to login using their username or email
     *
     * @param Request $request The HTTP request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (
            //Username authentication
            Auth::attempt([
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ], $remember)

            ||

            //Email authentication
            Auth::attempt([
                'email' => $request->input('username'),
                'password' => $request->input('password')
            ], $remember)
        ) {
            return redirect($this->redirectTo);
        }

        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['login' => 'Invalid username/email and password combination.']);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'email' => 'email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        //If no email was passed set it to null
        if (trim($data['email']) == '') {
            $data['email'] = null;
        }

        //Create the user
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'rank' => UserRankEnum::User
        ]);
    }
}
