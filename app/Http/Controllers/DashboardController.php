<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard for the signed-in user.
     */
    public function index(Request $request): View
    {
        return view('userzone.dashboard');
    }
}
