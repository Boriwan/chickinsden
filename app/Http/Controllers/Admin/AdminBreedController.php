<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\FlashesNotifications;
use App\Http\Controllers\Controller;
use App\Models\Breed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminBreedController extends Controller
{
    use FlashesNotifications;

    /**
     * Validation rules shared by store() and update().
     *
     * @return array<string, string>
     */
    private function rules(?Breed $breed = null): array
    {
        $unique = Rule::unique('breeds', 'name');

        if ($breed !== null) {
            $unique = $unique->ignore($breed->id);
        }

        return [
            'name' => ['required', 'string', 'max:20', $unique],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * List every breed.
     */
    public function index(): View
    {
        $breeds = Breed::withCount('chickens')->orderBy('name')->get();

        return view('admin.breeds.index', compact('breeds'));
    }

    /**
     * Show the form for creating a new breed.
     */
    public function create(): View
    {
        return view('admin.breeds.create');
    }

    /**
     * Store a newly created breed.
     */
    public function store(Request $request): RedirectResponse
    {
        Breed::create($request->validate($this->rules()));

        return $this->notify(
            redirect()->route('admin.breeds.index'),
            'The breed was added.',
            'created',
        );
    }

    /**
     * Show the form for editing a breed.
     */
    public function edit(Breed $breed): View
    {
        return view('admin.breeds.edit', compact('breed'));
    }

    /**
     * Update a breed.
     */
    public function update(Request $request, Breed $breed): RedirectResponse
    {
        $breed->update($request->validate($this->rules($breed)));

        return $this->notify(
            redirect()->route('admin.breeds.index'),
            "{$breed->name} was updated.",
            'updated',
        );
    }

    /**
     * Delete a breed that no chicken uses.
     *
     * chickens.breed_id is non-nullable, so removing a breed in use would fail
     * the foreign key. Refuse it with a message naming the chickens instead of
     * letting the database raise an error at the user.
     */
    public function destroy(Breed $breed): RedirectResponse
    {
        $name = $breed->name;
        $count = $breed->chickens()->count();

        if ($count > 0) {
            return redirect()
                ->route('admin.breeds.index')
                ->with('notice', [
                    [
                        'message' => "{$name} is still used by {$count} chicken(s). Move them to another breed first.",
                        'action' => 'error',
                    ],
                ]);
        }

        $breed->delete();

        return $this->notify(
            redirect()->route('admin.breeds.index'),
            "{$name} was deleted.",
            'deleted',
        );
    }
}
