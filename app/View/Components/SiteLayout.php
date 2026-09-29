<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SiteLayout extends Component
{
    public $menu;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->menu = [
            ['label' => '🐓 Chickens', 'route' => 'chickens.index'],
            ['label' => '📂 breeds', 'route' => 'breeds.index'],
            ['label' => 'ℹ️ About', 'route' => 'about'],
            ['label' => '⚙️ Admin', 'route' => 'admin.index'],
            ['label' => '➕ Add Chicken', 'route' => 'admin.chickens.create'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.site-layout');
    }
}
