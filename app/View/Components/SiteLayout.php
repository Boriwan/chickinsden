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
        $user = auth()->user();

        $this->menu = [];

        // Chickens are private, so the link is hidden from guests rather than
        // offering a link that would bounce them to the login screen.
        if ($user) {
            $this->menu[] = ['label' => 'Chickens', 'route' => 'chickens.index', 'icon' => 'egg'];
        }

        $this->menu[] = ['label' => 'Breeds Wiki', 'route' => 'breeds.index', 'icon' => 'book'];
        $this->menu[] = ['label' => 'About', 'route' => 'about', 'icon' => 'info'];

        if ($user) {
            if ($user->is_admin) {
                $this->menu[] = ['label' => 'Admin', 'route' => 'admin.index', 'icon' => 'settings'];
            }

            $this->menu[] = ['label' => 'Add Chicken', 'route' => 'chickens.create', 'icon' => 'plus'];
        } else {
            $this->menu[] = ['label' => 'Login', 'route' => 'login', 'icon' => 'user'];
            $this->menu[] = [
                'label' => 'Register',
                'route' => 'register',
                'icon' => 'user-plus',
                'highlight' => true,
            ];
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
