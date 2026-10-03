<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use Illuminate\View\View;

class BreedController extends Controller
{
    /**
     * List every breed as a reference overview.
     *
     * No chicken counts here: the wiki explains breeds, it is not a report on
     * the flock. The admin area carries the counts.
     */
    public function index(): View
    {
        $breeds = Breed::orderBy('name')->get();

        return view('breeds.index', compact('breeds'));
    }

    /**
     * Show a single breed entry.
     *
     * Writing lives in Admin\BreedController. Breeds are shared by the whole
     * app rather than owned by a user, so only admins create or change them.
     */
    public function show(Breed $breed): View
    {
        return view('breeds.show', compact('breed'));
    }
}
