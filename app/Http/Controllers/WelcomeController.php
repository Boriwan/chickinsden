<?php

namespace App\Http\Controllers;

use App\Models\Chicken;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page to guests, or the dashboard summary to signed-in users.
     */
    public function index(Request $request): View
    {
        $data = $request->user() ? $this->flockSummary($request) : [];

        return view('welcome', $data);
    }

    public function about(): View
    {
        return view('about');
    }

    /**
     * Summarise the signed-in user's own chickens for the dashboard.
     *
     * @return array<string, mixed>
     */
    private function flockSummary(Request $request): array
    {
        $chickens = Chicken::where('user_id', $request->user()->id);

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

        return compact('stats', 'recent', 'breedBreakdown');
    }
}
