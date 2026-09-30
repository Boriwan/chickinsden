<?php

namespace App\Http\Controllers;

use App\Models\Chicken;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show a summary of the signed-in user's own chickens.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $chickens = Chicken::where('user_id', $user->id);

        $stats = [
            'total' => (clone $chickens)->count(),
            'males' => (clone $chickens)->where('gender', 'male')->count(),
            'females' => (clone $chickens)->where('gender', 'female')->count(),
            'breeds' => (clone $chickens)->distinct()->count('breed_id'),
        ];

        $recent = (clone $chickens)
            ->with('breed')
            ->latest()
            ->take(5)
            ->get();

        $breedBreakdown = (clone $chickens)
            ->with('breed')
            ->get()
            ->groupBy(fn (Chicken $chicken) => $chicken->breed?->name ?? 'Unknown')
            ->map->count()
            ->sortDesc()
            ->take(5);

        return view('userzone.dashboard', compact('stats', 'recent', 'breedBreakdown'));
    }
}
