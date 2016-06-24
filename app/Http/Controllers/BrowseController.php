<?php

namespace App\Http\Controllers;

use App\Models\Breach;
use App\Models\Organisation;
use App\Models\Tag;
use App\Http\Requests;
use Illuminate\View\View;

class BrowseController extends Controller
{
    /**
     * Browse a list of organisation tags.
     *
     * @return View The pages view
     */
    public function browse(): View
    {
        $data['tags'] = Tag::orderBy('organisation_count', 'desc')->take(50)->get();

        return view('browse.list', $data);
    }

    /**
     * Browse a specific tag.
     *
     * @param string $tag The tag slug
     * @return View The pages view
     */
    public function browseTag(string $tag): View
    {
        $data['tag'] = Tag::findOrFail($tag);
        //Find all organisations that have the specific tag
        $data['organisations'] = Organisation
            ::whereHas('tags', function ($query) use ($tag) {
                $query->where('id', $tag);
            })
            ->status(['Accepted'])
            ->get();

        return view('browse.tag', $data);
    }

    /**
     * Browse a list of recent breaches
     *
     * @return View The pages view
     */
    public function recent(): View
    {
        $data['breaches'] = Breach::recent(50)->get();

        return view('browse.recent', $data);
    }
}
