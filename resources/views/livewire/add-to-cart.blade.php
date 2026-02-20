<div>
    @if($showQuantity)
        <div class="mb-2 quantity-controls d-flex align-items-center">
            <button wire:click="decrement" class="btn btn-sm btn-outline-secondary" 
                    {{ $quantity <= 1 ? 'disabled' : '' }}>
                <i class="fas fa-minus"></i>
            </button>
            <input type="number" wire:model="quantity" wire:change="updateQuantity($event.target.value)" 
                   class="mx-2 text-center form-control form-control-sm" style="width: 60px;" 
                   min="1" max="{{ $product->stock ?? 1 }}">
            <button wire:click="increment" class="btn btn-sm btn-outline-secondary"
                    {{ $product && $quantity >= $product->stock ? 'disabled' : '' }}>
                <i class="fas fa-plus"></i>
            </button>
        </div>
    @endif
    
    @if($buttonType == 'icon')
        <a href="javascript:void(0)" wire:click="addToCart" class="add-to-cart" 
           title="{{ $isInCart ? 'Update Cart' : 'Add to Cart' }}">
            <i class="fas fa-shopping-cart"></i>
            @if($isInCart)
                <span class="cart-badge">{{ $quantity }}</span>
            @endif
        </a>
        @if($isInCart)
            <a href="javascript:void(0)" wire:click="removeFromCart" class="text-danger ms-1" 
               title="Remove from Cart" style="font-size: 0.8rem;">
                <i class="fas fa-trash"></i>
            </a>
        @endif
    @else
        @if($isInCart)
            <div class="gap-2 d-flex align-items-center">
                <button wire:click="removeFromCart" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash me-1"></i> Remove
                </button>
                <button wire:click="addToCart" class="common_btn">
                    <i class="fas fa-sync me-2"></i> Update ({{ $quantity }})
                </button>
            </div>
        @else
            <button wire:click="addToCart" class="common_btn" 
                    {{ $product && $product->stock <= 0 ? 'disabled' : '' }}>
                <i style="transform: rotate(0)"class="fas fa-shopping-cart me-2"></i>
                {{ $product && $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
        @endif
    @endif
    
    @if($product && $product->stock <= 0)
        <div class="mt-1">
            <small class="text-danger">
                <i class="fas fa-exclamation-circle"></i> Out of Stock
            </small>
        </div>
    @elseif($product && $product->stock < 10)
        <div class="mt-1">
            <small class="text-warning">
                <i class="fas fa-exclamation-triangle"></i> Only {{ $product->stock }} left
            </small>
        </div>
    @endif
</div>