<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Chicken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChickenController extends Controller
{
    /**
     * Validation rules shared by store() and update().
     *
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|after_or_equal:1990-01-01|before_or_equal:today',
            'breed_id' => 'required|integer|exists:breeds,id',
            'height' => 'nullable|numeric|min:1|max:100',
            'weight' => 'nullable|in:light,medium,heavy',
        ];
    }

    /**
     * Show the chickens visible to the signed-in user.
     *
     * Admins see every chicken, everyone else sees only their own.
     */
    public function index(Request $request): View
    {
        $chickens = $request->user()->is_admin
            ? Chicken::with('breed')->latest()->get()
            : Chicken::with('breed')->where('user_id', $request->user()->id)->latest()->get();

        return view('chickens.index', compact('chickens'));
    }

    /**
     * Show the form for creating a new chicken.
     */
    public function create(): View
    {
        return view('chickens.create', ['breeds' => Breed::orderBy('name')->get()]);
    }

    /**
     * Store a newly created chicken for the signed-in user.
     */
    public function store(Request $request): RedirectResponse
    {
        $chicken = Chicken::create([
            ...$request->validate($this->rules()),
            'user_id' => $request->user()->id,
        ]);

        return $this->redirectToIndex($request)
            ->with('status', "{$chicken->name} was added.");
    }

    /**
     * Display the specified chicken.
     */
    public function show(Chicken $chicken): View
    {
        $this->authorize('view', $chicken);

        $chicken->load('breed');

        return view('chickens.show', compact('chicken'));
    }

    /**
     * Show the form for editing the specified chicken.
     */
    public function edit(Chicken $chicken): View
    {
        $this->authorize('update', $chicken);

        return view('chickens.edit', [
            'chicken' => $chicken,
            'breeds' => Breed::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified chicken.
     */
    public function update(Request $request, Chicken $chicken): RedirectResponse
    {
        $this->authorize('update', $chicken);

        $chicken->update($request->validate($this->rules()));

        return $this->redirectToIndex($request)
            ->with('status', "{$chicken->name} was updated.");
    }

    /**
     * Delete the specified chicken.
     */
    public function destroy(Request $request, Chicken $chicken): RedirectResponse
    {
        $this->authorize('delete', $chicken);

        $name = $chicken->name;
        $chicken->delete();

        return $this->redirectToIndex($request)
            ->with('status', "{$name} was deleted.");
    }

    /**
     * Send admins back to the admin table and users back to their own list.
     */
    private function redirectToIndex(Request $request): RedirectResponse
    {
        return redirect()->route(
            $request->user()->is_admin ? 'admin.chickens.index' : 'chickens.index'
        );
    }
}
