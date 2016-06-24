<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Breach;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use phpDocumentor\Reflection\DocBlock\Tag;

class DashboardController extends Controller
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
     * View the user dashboard.
     *
     * @return View The pages view
     */
    public function dashboard(): View
    {
        $data = [];

        return view('user.dashboard', $data);
    }

    /**
     * List all organisations the user has submitted.
     *
     * @return View The pages view
     */
    public function listOrganisations(): View
    {
        $data['organisations'] = Organisation
            ::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.organisations.list', $data);
    }

    /**
     * List all breaches the user has submitted.
     *
     * @param Request $request The HTTP request
     * @return View The pages view
     */
    public function listBreaches(Request $request): View
    {
        $data['breaches'] = Breach
            ::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        //If the user has searched for an organisation to add a breach to...
        if ($request->has('q')) {

            $data['searchTerm'] = $request->input('q');

            //Only make the search if more than one character was provided
            if (strlen($request->input('q')) > 1) {
                $data['organisations'] = Organisation::search($request->input('q'), $showSubmitted = true)->get();
            } else {
                $data['organisations'] = [];
            }
        }

        return view('user.breaches.list', $data);
    }

    /**
     * View a specific organisation that a user submitted.
     *
     * @param int $organisationId The ID of the organisation
     * @return View The pages view
     */
    public function getOrganisation(int $organisationId): View
    {
        $data['organisation'] = Organisation
            ::where('id', $organisationId)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('user.organisations.view', $data);
    }

    /**
     * View a specific breach that a user submitted.
     *
     * @param int $breachId The ID of the breach
     * @return View The pages view
     */
    public function getBreach(int $breachId): View
    {
        $data['breach'] = Breach
            ::where('id', $breachId)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('user.breaches.view', $data);
    }
}
