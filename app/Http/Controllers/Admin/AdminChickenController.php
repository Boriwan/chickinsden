<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chicken;

class AdminChickenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chickens = Chicken::all();
        return view('admin.chickens.index', compact('chickens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.chickens.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //    validate($request, [
        //         'name' => 'required|string|max:255',
        //         'age' => 'required|integer|min:0',
        //         'breed' => 'required|string|max:255',
        //     ]);

        Chicken::create([
            'name' => $request->input('name'),
            'gender' => $request->input('gender'),
            'born_date' => $request->input('born_date'),
            'breed_id' => $request->input('breed_id'),
            'den_id' => $request->input('den_id'),
            'height' => $request->input('height'),
            'weight' => $request->input('weight'),
        ]);

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
        return view('admin.chickens.edit', compact('chicken'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chicken $chicken)
    {
        $chicken->update([
            'name' => $request->input('name'),
            'gender' => $request->input('gender'),
            'born_date' => $request->input('born_date'),
            'breed_id' => $request->input('breed_id'),
            'den_id' => $request->input('den_id'),
            'height' => $request->input('height'),
            'weight' => $request->input('weight'),
        ]);

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
