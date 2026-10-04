<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreedController extends Controller
{
    /**
     * List breeds as a reference overview.
     *
     * No chicken counts here: the wiki explains breeds, it is not a report on
     * the flock. The admin area carries the counts.
     *
     * With ?used=1 the list narrows to breeds that actually have a chicken,
     * scoped the same way the dashboard counts them: the signed-in user's own
     * chickens, or every chicken in the app for an admin. Without that shared
     * scope the card would report one number and this page another.
     *
     * The plain wiki stays the default, since it is the better answer when
     * someone just wants to look a breed up.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $breeds = Breed::query()
            ->when(
                $user !== null && $request->boolean('used'),
                fn (Builder $query) => $query->whereHas(
                    'chickens',
                    fn (Builder $chickens) => $user->is_admin
                        ? $chickens
                        : $chickens->where('user_id', $user->id)
                )
            )
            ->orderBy('name')
            ->get();

        return view('breeds.index', [
            'breeds' => $breeds,
            'onlyUsed' => $request->boolean('used'),
        ]);
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
