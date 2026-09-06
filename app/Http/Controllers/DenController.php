<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Den;

class DenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dens = Den::all();

        return view('dens.index', compact('dens'));
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
    public function show(Den $den)
    {
        //return view('dens.show', compact('den'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Den $den)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Den $den)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Den $den)
    {
        //
    }
}
