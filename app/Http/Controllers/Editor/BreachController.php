<?php

namespace App\Http\Controllers\Editor;

use App\Models\Breach;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BreachController extends Controller
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
     * List all breaches that have yet to be approved.
     *
     * @return View The pages view
     */
    public function list(): View
    {
        $data['breaches'] = Breach
            ::status(['Submitted'])
            ->orderBy('updated_at')
            ->get();

        return view('editor.breaches.list', $data);
    }

    /**
     * View a single unapproved breach and compare it with the previous revision.
     *
     * @param int $breachId The breach to view
     * @return View The pages view
     */
    public function view(int $breachId): View
    {
        $data['breach'] = Breach::findOrFail($breachId);

        //If there is a previous revision fetch it
        if ($data['breach']->previous_id !== null) {
            $data['previousBreach'] = Breach::findOrFail($data['breach']->previous_id);
        }

        return view('editor.breaches.view', $data);
    }

    /**
     * Update the status of a breach from Submitted to Accepted or Rejected.
     *
     * @param Request $request The HTTP request
     * @param int $breachId The breach to update
     * @return RedirectResponse
     */
    public function status(Request $request, int $breachId): RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required|in:RejectedInfo,RejectedDuplicate,RejectedSource,Accepted'
        ]);

        $status = $request->input('status');

        //Update the breach status
        $breach = Breach::findOrFail($breachId);
        $breach->status = $status;
        $breach->save();

        //If the status is accepted update the previous revisions to superseded
        if ($status === 'Accepted') {
            $previous = $breach->getPreviousRevisions();
            Breach::whereIn('id', $previous)->update(['status' => 'Superseded']);
        }

        //Update the users approved breaches
        $creator = $breach->creator;
        $creator->approved_breaches++;
        $creator->save();

        return redirect()->action('Editor\BreachController@list');
    }
}
