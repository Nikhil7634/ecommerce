<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NavbarCartCount extends Component
{
    public $cartCount = 0;
    public $wishlistCount = 0;

    protected $listeners = [
        'cartUpdated' => 'updateCounts',
        'wishlistUpdated' => 'updateCounts', // if you have wishlist events
    ];

    public function mount()
    {
        $this->updateCounts();
    }

    public function updateCounts()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->cartCount = $user->cart()->count();
            $this->wishlistCount = $user->wishlist()->count();
        } else {
            $this->cartCount = 0;
            $this->wishlistCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.navbar-cart-count');
    }
}