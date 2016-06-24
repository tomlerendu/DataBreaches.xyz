<?php

namespace App\Http\Controllers;

use App\Models\Breach;
use App\Models\Organisation;
use Illuminate\View\View;

class OrganisationController extends Controller
{
    /**
     * View an organisation and all its breaches.
     *
     * @param string $organisationSlug The organisation slug ID
     * @return View The pages view
     */
    public function view(string $organisationSlug): View
    {
        $organisation = Organisation
            ::slug($organisationSlug)
            ->firstOrFail();

        $data['organisation'] = $organisation;
        $data['breaches'] = Breach
            ::where('organisation_id', $organisation->id)
            ->status(['Accepted'])
            ->orderBy('date_occurred', 'desc')
            ->get();

        return view('organisation.view', $data);
    }
}