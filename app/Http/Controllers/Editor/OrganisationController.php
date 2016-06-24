<?php

namespace App\Http\Controllers\Editor;

use App\Models\Breach;
use App\Models\Organisation;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganisationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.rank.editor');
    }

    /**
     * List the organisations that have yet to be accepted.
     *
     * @return View The pages view
     */
    public function list(): View
    {
        $data['organisations'] = Organisation
            ::status(['Submitted'])
            ->orderBy('updated_at')
            ->get();

        return view('editor.organisations.list', $data);
    }

    /**
     * View an organisation and compare it to the previous revision.
     *
     * @param int $organisationId The ID of the organisation
     * @return View The pages view
     */
    public function view(int $organisationId): View
    {
        $data['organisation'] = Organisation::findOrFail($organisationId);

        //If there was a previous revision
        if ($data['organisation']->previous_id !== null) {
            $data['previousOrganisation'] = Organisation::findOrFail($data['organisation']->previous_id);
        }

        return view('editor.organisations.view', $data);
    }

    /**
     * Update the status of an organisation from Submitted to Accepted or Rejected.
     *
     * @param Request $request
     * @param int $organisationId The ID of the organisation
     * @return RedirectResponse
     */
    public function status(Request $request, int $organisationId): RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required|in:RejectedInfo,RejectedDuplicate,Accepted'
        ]);

        $status = $request->input('status');

        //Update the organisation status
        $organisation = Organisation::findOrFail($organisationId);
        $organisation->status = $status;
        $organisation->save();

        //If the organisation was changed to accepted change the previous revisions to superseded
        if ($status === 'Accepted') {
            $previous = $organisation->getPreviousRevisions();
            Organisation::whereIn('id', $previous)->update(['status' => 'Superseded']);
            //Move any breaches to the newly approved organisation
            Breach::whereIn('organisation_id', $previous)->update(['organisation_id' => $organisation->id]);
        }

        //Update the users approved organisations
        $creator = $organisation->creator;
        $creator->approved_organisations++;
        $creator->save();

        return redirect()->action('Editor\OrganisationController@list');
    }
}
