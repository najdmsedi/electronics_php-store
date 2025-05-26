// Custom JavaScript for Electronics Store

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Quantity input validation for product detail page
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            const value = parseInt(this.value);
            
            if (value > max) {
                this.value = max;
                alert('Maximum available quantity is ' + max);
            } else if (value < min) {
                this.value = min;
            }
        });
    }
    
    // Form validation for checkout
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(event) {
            const paymentMethod = document.getElementById('payment_method');
            const terms = document.getElementById('terms');
            
            if (paymentMethod.value === '') {
                event.preventDefault();
                alert('Please select a payment method');
                paymentMethod.focus();
                return false;
            }
            
            if (!terms.checked) {
                event.preventDefault();
                alert('Please agree to the terms and conditions');
                terms.focus();
                return false;
            }
        });
    }
    
    // Image preview for admin product forms
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxWidth = '200px';
                    
                    const previewContainer = document.getElementById('image-preview');
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(preview);
                    } else {
                        const newPreviewContainer = document.createElement('div');
                        newPreviewContainer.id = 'image-preview';
                        newPreviewContainer.className = 'mt-2';
                        newPreviewContainer.appendChild(preview);
                        imageInput.parentNode.appendChild(newPreviewContainer);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
