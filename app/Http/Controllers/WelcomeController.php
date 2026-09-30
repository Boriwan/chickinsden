<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the marketing page to guests and the dashboard to signed-in users.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

        return view('welcome');
    }

    public function about(): View
    {
        return view('about');
    }
}
