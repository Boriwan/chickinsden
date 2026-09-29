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
            ['label' => 'Chickens', 'route' => 'chickens.index', 'icon' => 'egg'],
            ['label' => 'Breeds', 'route' => 'breeds.index', 'icon' => 'folder'],
            ['label' => 'About', 'route' => 'about', 'icon' => 'info'],
        ];

        if (auth()->check()) {
            $this->menu[] = ['label' => 'Admin', 'route' => 'admin.index', 'icon' => 'settings'];
            $this->menu[] = ['label' => 'Add Chicken', 'route' => 'admin.chickens.create', 'icon' => 'plus'];
        } else {
            $this->menu[] = ['label' => 'Login', 'route' => 'login', 'icon' => 'user'];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.site-layout');
    }
}
