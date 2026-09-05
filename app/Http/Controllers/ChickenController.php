<?php

namespace App\Http\Controllers;

use App\Models\Chicken;
use Illuminate\Http\Request;

class ChickenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chickens = Chicken::all();

        return view('chickens.index', compact('chickens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Chicken $chicken)
    {
        return $chicken;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chicken $chicken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chicken $chicken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chicken $chicken)
    {
        //
    }
}
