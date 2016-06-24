<?php

namespace App\Http\Controllers;

use App\Models\Breach;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * View the home page.
     *
     * @return View The pages view
     */
    public function view(): View
    {
        $data['breaches'] = Breach::recent(10)->get();

        return view('home', $data);
    }

    /**
     * Search for an organisation.
     *
     * @param Request $request The HTTP request
     * @return View The pages view
     */
    public function search(Request $request): View
    {
        $query = $request->input('q');

        $data['searchTerm'] = $query;

        //If a sufficiently long search term was given search for it
        if (strlen($query) > 1) {
            $data['organisations'] = Organisation::search($query)->get();
        } else {
            $data['organisations'] = [];
        }

        return view('search', $data);
    }
}
