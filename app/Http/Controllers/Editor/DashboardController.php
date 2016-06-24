<?php

namespace App\Http\Controllers\Editor;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @var array A list of commands that can be run from the editor dashboard
     */
    private $commands = [
        'organisations:recalculate' => 'Recalculate organisation scores',
        'tags:recalculate' => 'Recalculate tag organisation counts'
    ];

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
     * View the editor dashboard.
     *
     * @return View The pages view
     */
    public function dashboard(): View
    {
        $data['commands'] = $this->commands;

        return view('editor.dashboard', $data);
    }

    /**
     * Run one of the app commands.
     *
     * @param string $command The name of the command to run
     * @return RedirectResponse
     */
    public function command($command): RedirectResponse
    {
        //Only call the command if it's in the allowed list
        if (!in_array($command, array_keys($this->commands))) {
            abort(404);
        }

        Artisan::call($command);

        return redirect()->back();
    }
}
