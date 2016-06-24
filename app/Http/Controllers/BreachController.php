<?php

namespace App\Http\Controllers;

use App\Models\Breach;
use Illuminate\View\View;

class BreachController extends Controller
{
    /**
     * View a breach.
     *
     * @param string $organisationSlug The organisation slug that the breach belongs to
     * @param int $breachId The breach ID
     * @return View The pages view
     */
    public function get(string $organisationSlug, int $breachId): View
    {
        //Find the approved breach for the breachId
        $breach = Breach
            ::where('id', $breachId)
            ->status(['Accepted'])
            ->first();

        if ($breach->organisation->slug !== $organisationSlug) {
            abort(404);
        }

        $data['organisation'] = $breach->organisation;
        $data['breaches'] = collect([$breach]);

        return view('organisation.breach', $data);
    }
}