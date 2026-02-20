<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistToggle extends Component
{
    public $productId;
    public $isInWishlist = false;
    public $buttonType = 'icon';

    // This refreshes the heart icon instantly when wishlist changes anywhere
    protected $listeners = ['wishlistUpdated' => '$refresh'];

    public function mount($productId, $buttonType = 'icon')
    {
        $this->productId = $productId;
        $this->buttonType = $buttonType;
        $this->checkStatus();
    }

    public function checkStatus()
    {
        if (Auth::check()) {
            $this->isInWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->exists();
        } else {
            $this->isInWishlist = false;
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            $this->dispatch('showLoginAlert');
            return;
        }

        $product = Product::find($this->productId);

        if (!$product) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Product not found.',
            ]);
            return;
        }

        if ($this->isInWishlist) {
            // Remove from wishlist
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->delete();

            $this->isInWishlist = false;
            $message = 'Product removed from wishlist.';
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
            ]);

            $this->isInWishlist = true;
            $message = 'Product added to wishlist!';
        }

        // Show success notification
        $this->dispatch('showNotification', [
            'type' => 'success',
            'message' => $message,
        ]);

        // CRITICAL: Dispatch the correct event name that NavbarCartCount listens to
        $this->dispatch('wishlistUpdated');
    }

    public function render()
    {
        return view('livewire.wishlist-toggle');
    }
}