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
        $this->menu = [['label' => '🪹 Dens', 'link' => '/dens'], ['label' => '🐓 Chickens', 'link' => '/chickens'], ['label' => 'ℹ️ About', 'link' => '/about'], ['label' => '⚙️ Admin', 'link' => '/admin/chickens'], ['label' => '➕ Add Chicken', 'link' => '/admin/chickens/create']];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.site-layout');
    }
}
