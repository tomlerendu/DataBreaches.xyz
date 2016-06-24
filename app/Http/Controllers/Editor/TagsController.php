<?php

namespace App\Http\Controllers\Editor;

use App\Models\Tag;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagsController extends Controller
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
     * List all tags in the system.
     *
     * @return View The pages view
     */
    public function list(): View
    {
        $data['tags'] = Tag::get();

        return view('editor.tags.view', $data);
    }

    /**
     * Add a new tag to the system.
     *
     * @param Request $request The HTTP request
     * @return RedirectResponse
     */
    public function submit(Request $request): RedirectResponse
    {
        $this->validate($request, [
           'tag' => 'required|unique:tags,name|max:255'
        ]);

        //Create the tag
        Tag::create([
            'id' => str_slug($request->input('tag')),
            'name' => $request->input('tag')
        ]);

        return redirect()->action('Editor\TagsController@list');
    }

    /**
     * Remove a tag from the system
     *
     * @param int $tagId The ID of the tag to remove
     * @return RedirectResponse
     */
    public function delete(string $tagId): RedirectResponse
    {
        $tag = Tag::findOrFail($tagId);
        $tag->delete();

        return redirect()->action('Editor\TagsController@list');
    }
}
