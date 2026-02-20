<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AddToCart extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;
    public $isInCart = false;
    public $buttonType = 'icon';
    public $showQuantity = false;

    // Match the dispatched event name exactly
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function mount($productId, $buttonType = 'icon', $showQuantity = false)
    {
        $this->productId = $productId;
        $this->product = Product::find($productId);
        $this->buttonType = $buttonType;
        $this->showQuantity = $showQuantity;
        $this->checkCartStatus();
    }

    public function checkCartStatus()
    {
        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->first();

            $this->isInCart = !is_null($cartItem);
            if ($this->isInCart && $cartItem) {
                $this->quantity = $cartItem->quantity;
            }
        }
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            $this->dispatch('showLoginAlert');
            return;
        }

        if (!$this->product) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Product not found.',
            ]);
            return;
        }

        if ($this->product->stock <= 0) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'This product is out of stock.',
            ]);
            return;
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $this->productId)
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $newQuantity = $cartItem->quantity + $this->quantity;

            if ($newQuantity > $this->product->stock) {
                $this->dispatch('showNotification', [
                    'type' => 'error',
                    'message' => 'Cannot add more than available stock.',
                ]);
                return;
            }

            $cartItem->update(['quantity' => $newQuantity]);
            $this->quantity = $newQuantity;

            $message = 'Cart updated successfully!';
        } else {
            // Add new item to cart
            if ($this->quantity > $this->product->stock) {
                $this->dispatch('showNotification', [
                    'type' => 'error',
                    'message' => 'Requested quantity exceeds available stock.',
                ]);
                return;
            }

            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
            ]);

            $this->isInCart = true;
            $message = 'Product added to cart successfully!';
        }

        // Success notification - NOW WORKS!
        $this->dispatch('showNotification', [
            'type' => 'success',
            'message' => $message,
        ]);

        // Refresh cart counter/header
        $this->dispatch('cartUpdated');
    }

    public function updateQuantity($newQuantity)
    {
        $newQuantity = (int) $newQuantity;

        if ($newQuantity < 1) {
            return;
        }

        if ($this->product && $newQuantity > $this->product->stock) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Quantity exceeds available stock.',
            ]);
            return;
        }

        $this->quantity = $newQuantity;

        if ($this->isInCart && Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->update(['quantity' => $this->quantity]);

            $this->dispatch('cartUpdated');
        }
    }

    public function increment()
    {
        $this->updateQuantity($this->quantity + 1);
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->updateQuantity($this->quantity - 1);
        }
    }

    public function removeFromCart()
    {
        if (!Auth::check()) {
            $this->dispatch('showLoginAlert');
            return;
        }

        Cart::where('user_id', Auth::id())
            ->where('product_id', $this->productId)
            ->delete();

        $this->isInCart = false;
        $this->quantity = 1;

        $this->dispatch('showNotification', [
            'type' => 'success',
            'message' => 'Product removed from cart.',
        ]);

        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.add-to-cart', [
            'product' => $this->product,
        ]);
    }
}