document.addEventListener('DOMContentLoaded', function() {
    // Aumentar cantidad
    document.querySelectorAll('.increase-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            updateQuantity(productId, 1);
        });
    });
    
    // Disminuir cantidad
    document.querySelectorAll('.decrease-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            updateQuantity(productId, -1);
        });
    });
    
    // Eliminar producto
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            if(confirm('¿Estás seguro de eliminar este producto de tu carrito?')) {
                removeFromCart(productId);
            }
        });
    });
    
    // Actualizar cantidad manualmente
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.getAttribute('data-id');
            const newQuantity = parseInt(this.value);
            
            if(newQuantity > 0) {
                updateCart(productId, newQuantity);
            } else {
                if(confirm('¿Estás seguro de eliminar este producto de tu carrito?')) {
                    removeFromCart(productId);
                } else {
                    this.value = 1;
                }
            }
        });
    });
    
    function updateQuantity(productId, change) {
        const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
        const newQuantity = parseInt(input.value) + change;
        
        if(newQuantity > 0) {
            input.value = newQuantity;
            updateCart(productId, newQuantity);
        }
    }
    
    function updateCart(productId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }
    
    function removeFromCart(productId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }
});