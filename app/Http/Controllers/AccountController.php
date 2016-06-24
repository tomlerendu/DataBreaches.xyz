<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.rank.user');
    }

    /**
     * View the user account settings
     *
     * @return View The pages view
     */
    public function view(): View
    {
        $user = Auth::user();

        $data = [
            'email' => $user->email,
            'username' => $user->username
        ];

        return view('auth.account', $data);
    }

    /**
     * Change a users password.
     *
     * @param Request $request The HTTP request
     * @return RedirectResponse
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        $user = Auth::user();

        //If the users entered the wrong current password redirect them with an error
        if (!$user->hasPassword($request->input('current_password'))) {
            return redirect()
                ->action('AccountController@view')
                ->withInput()
                ->withErrors(['current_password' => 'You entered your current password incorrectly.']);
        }

        $user->changePassword($request->input('new_password'));
        $user->save();

        return redirect()
            ->action('AccountController@view')
            ->with('password-updated', true);
    }
}
