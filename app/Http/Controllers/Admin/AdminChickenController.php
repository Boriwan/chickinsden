<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chicken;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminChickenController extends Controller
{
    /**
     * Show every chicken, including who owns it.
     *
     * Write operations live in ChickenController; the policy lets admins
     * act on any chicken, so duplicating them here would be redundant.
     */
    public function index(Request $request): View
    {
        $chickens = Chicken::with(['breed', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.chickens.index', compact('chickens'));
    }
}
