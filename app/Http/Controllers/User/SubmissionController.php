<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\BreachPostRequest;
use App\Http\Requests\OrganisationPostRequest;
use App\Models\Breach;
use App\Models\Organisation;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
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
     * Edit an organisation.
     *
     * @param int $organisationId The organisation ID
     * @return RedirectResponse
     */
    public function editOrganisation(int $organisationId): RedirectResponse
    {
        return redirect()
            ->action('User\SubmissionController@submitOrganisation')
            ->with('previousId', $organisationId);
    }

    /**
     * Submit an organisation for approval.
     *
     * @param Request $request The HTTP request
     * @return Response The HTTP response
     */
    public function submitOrganisation(Request $request): Response
    {
        $data['tags'] = Tag::all();

        $previousId = $request->session()->get('previousId');

        //If a previous organisation was passed
        if ($previousId) {

            //Fetch an organisation that the current user can edit or supersede
            $organisation = Organisation::with('tags')
                ->where('id', $previousId)
                ->canDoEither(Auth::user()->id, $edit = true, $superseded = true)
                ->firstOrFail();

            $input = $organisation->toArray();
            $input['previous_id'] = $previousId;

            //Add the organisation to the session
            $request->session()->flashInput($input);
        }

        return view('user.organisations.submit', $data);
    }

    /**
     * Process an organisation submission.
     *
     * @param OrganisationPostRequest $request The validated HTTP request
     * @return RedirectResponse
     */
    public function processOrganisation(OrganisationPostRequest $request): RedirectResponse
    {
        //Get the organisation if the user can edit it
        $organisation = Organisation
            ::where('id', $request->input('previous_id', -1))
            ->canEdit(Auth::user()->id)
            ->first();

        //If the user cannot edit the organisation create a new one
        if ($organisation === null) {
            $organisation = new Organisation();
            $organisation->previous_id = $request->input('previous_id');
        }

        //Set the organisation attributes
        $organisation->slug = str_slug($request->input('name'));
        $organisation->name = $request->input('name');
        $organisation->styled_name = $request->input('styled_name');
        $organisation->registration_number = $request->input('registration_number');
        $organisation->incorporated_on = $request->input('incorporated_on');
        $organisation->user_id = Auth::user()->id;

        $organisation->save();

        //Create the organisation tags
        $tagIds = array_keys($request->input('tags'));
        $tags = Tag::find($tagIds);
        $organisation->tags()->detach();
        $organisation->tags()->saveMany($tags);

        //Redirect the user to the organiation view page
        return redirect()
            ->action('User\DashboardController@getOrganisation', [
               'organisationId' => $organisation->id
            ]);
    }

    /**
     * Delete an organisation.
     *
     * @param int $organisationId The organisation ID
     * @return RedirectResponse
     */
    public function deleteOrganisation(int $organisationId): RedirectResponse
    {
        //Find an organisation with the id specified that the user has permission to delete
        $organisation = Organisation
            ::where('id', $organisationId)
            ->canDelete(Auth::user()->id)
            ->firstOrFail();

        $organisation->delete();

        return redirect()
            ->action('User\DashboardController@listOrganisations');
    }


    /**
     * Edit a breach.
     *
     * @param int $organisationId The organisation ID of the breach
     * @param int $breachId The breach ID to edit
     * @return RedirectResponse
     */
    public function editBreach(int $organisationId, int $breachId): RedirectResponse
    {
        return redirect()
            ->action('User\SubmissionController@submitBreach', $organisationId)
            ->with('previousId', $breachId);
    }

    /**
     * Submit a breach for approval.
     *
     * @param Request $request The HTTP request
     * @param int $organisationId The organisation ID
     * @return Response The HTTP response The HTTP response
     */
    public function submitBreach(Request $request, int $organisationId): Response
    {
        $organisation = null;

        $previousId = $request->session()->get('previousId');

        //If a previous breach ID was given
        if ($previousId) {

            //Find a breach that the user can edit and
            $breach = Breach
                ::where('id', $previousId)
                ->canDoEither(Auth::user()->id, $edit = true, $superseded = true)
                ->firstOrFail();

            //If organisation doesn't match pervious organisation abort
            if ($breach->organisation->id != $organisationId) {
                abort(404);
            }

            $input = $breach->toArray();
            $input['previous_id'] = $previousId;

            $request->session()->flashInput($input);

            $organisation = $breach->organisation;
        } else {

            $organisation = Organisation
                ::where('id', $organisationId)
                ->canDoEither(Auth::user()->id, $edit = true, $superseded = true)
                ->firstOrFail();
        }

        $data['organisation'] = $organisation;

        return view('user.breaches.submit', $data);
    }

    /**
     * Store a user submitted breach.
     *
     * @param BreachPostRequest $request The validated HTTP request
     * @param int $organisationId The organisation ID
     * @return RedirectResponse
     */
    public function processBreach(BreachPostRequest $request, int $organisationId): RedirectResponse
    {
        //Find the relevant organisation
        $organisation = Organisation
            ::where('id', $organisationId)
            ->canDoEither(Auth::user()->id, $edit = true, $superseded = true)
            ->firstOrFail();

        $breach = Breach
            ::where('id', $request->input('previous_id', -1))
            ->canEdit(Auth::user()->id)
            ->first();

        if ($breach === null) {
            $breach = new Breach();
            $breach->previous_id = $request->input('previous_id');
        }

        //Set the properties on the model
        $breach->organisation()->associate($organisation);
        $breach->method = $request->input('method');
        $breach->summary = $request->input('summary');
        $breach->data_leaked = $request->input('data_leaked', []);
        $breach->date_occurred = $request->input('date_occurred');
        $breach->people_affected = $request->input('people_affected');
        $breach->previously_known = $request->has('previously_known');
        $breach->response_stance = $request->input('response_stance');
        $breach->response_patched = $request->input('response_patched');
        $breach->response_customers_informed = $request->input('response_customers_informed');
        $breach->source_url = $request->input('source_url');
        $breach->source_name = $request->input('source_name');
        $breach->more_url = $request->input('more_url');
        $breach->user_id = Auth::user()->id;

        $breach->save();

        //Redirect the user to the breach page
        return redirect()
            ->action('User\DashboardController@getBreach',[
                'breachId' => $breach->id
            ]);
    }

    /**
     * Delete a user submitted breach
     *
     * @param int $organisationId The ID of the organisation the breach belongs to
     * @param int $breachId The breach ID
     * @return RedirectResponse
     */
    public function deleteBreach(int $organisationId, int $breachId): RedirectResponse
    {
        //Find a breach that the user can delete with the specific id
        $breach = Breach
            ::where('id', $breachId)
            ->where('organisation_id', $organisationId)
            ->canDelete(Auth::user()->id)
            ->firstOrFail();

        $breach->delete();

        return redirect()
            ->action('User\DashboardController@listBreaches');
    }
}
