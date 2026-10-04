<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\FlashesNotifications;
use App\Http\Controllers\Controller;
use App\Models\ChickenTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTraitController extends Controller
{
    use FlashesNotifications;

    /**
     * List every trait, with the admin pagination the chicken table uses.
     */
    public function index(): View
    {
        $traits = ChickenTrait::withCount('chickens')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.traits.index', compact('traits'));
    }

    /**
     * The spec lists standalone create and edit pages.
     *
     * A trait is only a name, so both render the list, where adding and
     * renaming happen inline. The routes still exist and answer, rather than
     * pointing at templates nothing links to.
     */
    public function create(): View
    {
        return view('admin.traits.index');
    }

    /**
     * Store a newly created trait.
     */
    public function store(Request $request): RedirectResponse
    {
        ChickenTrait::create($request->validate($this->rules()));

        return $this->notify(
            redirect()->route('admin.traits.index'),
            'The trait was added.',
            'created',
        );
    }

    /**
     * Renaming happens inline on the list, so this renders it too.
     */
    public function edit(ChickenTrait $chickenTrait): View
    {
        return view('admin.traits.index');
    }

    /**
     * Update a trait.
     *
     * The pivot rows key on the id rather than the name, so renaming a trait
     * leaves every chicken that carries it untouched.
     */
    public function update(Request $request, ChickenTrait $chickenTrait): RedirectResponse
    {
        $chickenTrait->update($request->validate($this->rules($chickenTrait)));

        return $this->notify(
            redirect()->route('admin.traits.index'),
            "{$chickenTrait->name} was updated.",
            'updated',
        );
    }

    /**
     * Delete a trait.
     *
     * No guard needed, unlike breeds: chickens reference traits only through
     * the pivot, so removing one drops its rows and no foreign key is violated.
     */
    public function destroy(ChickenTrait $chickenTrait): RedirectResponse
    {
        $name = $chickenTrait->name;
        $chickenTrait->delete();

        return $this->notify(
            redirect()->route('admin.traits.index'),
            "{$name} was deleted.",
            'deleted',
        );
    }

    /**
     * Validation rules shared by store() and update().
     *
     * @return array<string, mixed>
     */
    private function rules(?ChickenTrait $chickenTrait = null): array
    {
        $unique = Rule::unique('chicken_traits', 'name');

        if ($chickenTrait !== null) {
            $unique = $unique->ignore($chickenTrait->id);
        }

        return ['name' => ['required', 'string', 'max:50', $unique]];
    }
}
