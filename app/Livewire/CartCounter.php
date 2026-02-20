<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartCounter extends Component
{
    public $count = 0;
    
    protected $listeners = ['cartItemAdded', 'cartItemUpdated', 'cartItemRemoved', 'cartUpdated'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        if (Auth::check()) {
            $this->count = \App\Models\Cart::where('user_id', Auth::id())->count();
        } else {
            $this->count = 0;
        }
    }

    public function cartItemAdded()
    {
        $this->updateCount();
    }

    public function cartItemUpdated()
    {
        $this->updateCount();
    }

    public function cartItemRemoved()
    {
        $this->updateCount();
    }

    public function cartUpdated()
    {
        $this->updateCount();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}