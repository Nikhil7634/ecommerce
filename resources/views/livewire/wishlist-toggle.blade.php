<div>
    @if($buttonType == 'icon')
        <!-- NOTE: wire:click="toggle" NOT wire:click="toggleWishlist" -->
        <button wire:click="toggle" 
                type="button"
                class="p-0 bg-transparent border-0 btn btn-link"
                title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
            @if($isInWishlist)
                <i class="fas fa-heart text-danger fs-5"></i>
            @else
                <i class="text-white far fa-heart fs-5"></i>
            @endif
        </button>
    @else
        <!-- NOTE: wire:click="toggle" NOT wire:click="toggleWishlist" -->
        <button wire:click="toggle" 
                type="button"
                class="btn {{ $isInWishlist ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart me-1"></i>
            {{ $isInWishlist ? 'In Wishlist' : 'Add to Wishlist' }}
        </button>
    @endif
</div>