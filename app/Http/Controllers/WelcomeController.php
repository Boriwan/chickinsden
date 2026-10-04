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
        $data = $request->user() ? $this->denSummary($request) : [];

        return view('welcome', $data);
    }

    public function about(): View
    {
        return view('about');
    }

    /**
     * Summarise the dashboard for the signed-in user.
     *
     * Ordinary users see their own den only. Admins see the whole app, because
     * they can already reach every chicken from the admin area, and a dashboard
     * that quietly hid most of it from them would be misleading. Every count on
     * the page goes through the same scope so the cards can never disagree with
     * the charts beside them.
     *
     * @return array<string, mixed>
     */
    private function denSummary(Request $request): array
    {
        $scope = fn ($query) => $query->when(
            ! $request->user()->is_admin,
            fn ($q) => $q->where('user_id', $request->user()->id)
        );

        $chickens = $scope(Chicken::query());

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

        // Keyed by breed id so the chart legend can link to the filtered list.
        $breedBreakdown = $scope(Chicken::query()->with('breed'))
            ->get()
            ->groupBy('breed_id')
            ->map(fn ($chickens, $breedId) => [
                'id' => (int) $breedId,
                'label' => $chickens->first()->breed?->name ?? 'Unknown',
                'count' => $chickens->count(),
            ])
            ->sortByDesc('count')
            ->values();

        // A chicken can carry several traits, so these counts are trait
        // assignments and add up to more than the number of chickens.
        $traitBreakdown = $scope(Chicken::query()->with('traits'))
            ->get()
            ->flatMap(fn (Chicken $chicken) => $chicken->traits
                ->map(fn ($trait) => ['id' => $trait->id, 'label' => $trait->name]))
            ->groupBy('id')
            ->map(fn ($assignments) => [
                'id' => $assignments->first()['id'],
                'label' => $assignments->first()['label'],
                'count' => $assignments->count(),
            ])
            ->sortByDesc('count')
            ->values();

        return compact('stats', 'recent', 'breedBreakdown', 'traitBreakdown');
    }
}
