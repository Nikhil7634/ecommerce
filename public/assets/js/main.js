     
    // Update quantity
    function updateQuantity(action) {
        const quantityInput = document.getElementById('productQuantity');
        const hiddenQuantity = document.getElementById('hiddenQuantity');
        let currentValue = parseInt(quantityInput.value) || 1;
        const maxStock = parseInt(quantityInput.getAttribute('max')) || 999;
        
        if (action === 'increase' && currentValue < maxStock) {
            currentValue++;
        } else if (action === 'decrease' && currentValue > 1) {
            currentValue--;
        }
        
        quantityInput.value = currentValue;
        hiddenQuantity.value = currentValue;
    }
    
    // Update hidden quantity when input changes
    function updateHiddenQuantity(value) {
        const hiddenQuantity = document.getElementById('hiddenQuantity');
        hiddenQuantity.value = value;
    }
    
    // Select variant
    function selectVariant(element, variantId) {
        // Remove active class from all items in the same group
        const parent = element.parentElement;
        const items = parent.querySelectorAll('li');
        items.forEach(item => item.classList.remove('active'));
        
        // Add active class to clicked item
        element.classList.add('active');
        
        // Update hidden variant input
        const variantInput = document.getElementById('selectedVariantId');
        if (variantInput) {
            variantInput.value = variantId;
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial hidden quantity
        const quantityInput = document.getElementById('productQuantity');
        const hiddenQuantity = document.getElementById('hiddenQuantity');
        if (quantityInput && hiddenQuantity) {
            hiddenQuantity.value = quantityInput.value;
        }
        
        // Set initial variant if exists
        const firstVariant = document.querySelector('[data-variant-id]');
        const variantInput = document.getElementById('selectedVariantId');
        if (firstVariant && variantInput) {
            variantInput.value = firstVariant.dataset.variantId;
        }
    });
 












    