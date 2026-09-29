<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Chicken;
use Illuminate\Http\Request;

class AdminChickenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->is_admin) {
            $chickens = Chicken::all();
        } else {
            $chickens = Chicken::where('user_id', auth()->user()->id)->get();
        }

        return view('admin.chickens.index', compact('chickens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breeds = Breed::all();

        return view('admin.chickens.create', compact('breeds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|after_or_equal:1990-01-01|before_or_equal:today',
            'breed_id' => 'required|integer|exists:breeds,id',
            'height' => 'nullable|numeric|min:1|max:100',
            'weight' => 'nullable|in:light,medium,heavy',
        ]);

        Chicken::create([...$validated, 'user_id' => $request->user()->id]);

        return redirect()->route('admin.chickens.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chicken $chicken)
    {
        $breeds = Breed::all();

        return view('admin.chickens.edit', compact('chicken', 'breeds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chicken $chicken)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|after_or_equal:1990-01-01|before_or_equal:today',
            'breed_id' => 'required|integer|exists:breeds,id',
            'height' => 'nullable|numeric|min:1|max:100',
            'weight' => 'nullable|in:light,medium,heavy',
        ]);

        $chicken->update($validated);

        return redirect()->route('admin.chickens.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chicken $chicken)
    {
        $chicken->delete();

        return redirect()->route('admin.chickens.index');
    }
}
