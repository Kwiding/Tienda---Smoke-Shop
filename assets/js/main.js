/**
 * GreenLeaf - Tienda de Cannabis
 * JavaScript Principal
 * 
 * Contiene todas las funcionalidades interactivas del sitio:
 * - Carrito de compras
 * - Verificación de edad
 * - Filtros de productos
 * - Animaciones y efectos
 * - Validaciones de formularios
 */

document.addEventListener('DOMContentLoaded', function() {
    // ===== VERIFICACIÓN DE EDAD =====
    initAgeVerification();
    
    // ===== CARRITO DE COMPRAS =====
    initCartFunctionality();
    
    // ===== FILTROS DE PRODUCTOS =====
    initProductFilters();
    
    // ===== ANIMACIONES Y EFECTOS =====
    initAnimations();
    
    // ===== VALIDACIONES DE FORMULARIOS =====
    initFormValidations();
    
    // ===== MENÚ MÓVIL =====
    initMobileMenu();
    
    // ===== GALERÍA DE PRODUCTOS =====
    initProductGallery();
});

/**
 * Inicializa la verificación de edad
 */
function initAgeVerification() {
    const ageForm = document.getElementById('ageVerificationForm');
    if (ageForm) {
        ageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const birthDate = new Date(this.birth_date.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age >= 18) {
                // Guardar en sessionStorage para no preguntar de nuevo
                sessionStorage.setItem('ageVerified', 'true');
                const overlay = document.querySelector('.age-verification-overlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            } else {
                window.location.href = 'https://www.google.com';
            }
        });
    }
}

/**
 * Funcionalidades del carrito de compras
 */
function initCartFunctionality() {
    // Añadir producto al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            addToCart(productId);
        });
    });
    
    // Actualizar cantidad en carrito
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.id;
            const quantity = parseInt(this.value);
            updateCartItem(productId, quantity);
        });
    });
    
    // Botones de incremento/decremento
    document.querySelectorAll('.increment-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('change'));
        });
    });
    
    document.querySelectorAll('.decrement-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Eliminar producto del carrito
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            removeFromCart(productId);
        });
    });
    
    // Vaciar carrito
    const emptyCartBtn = document.getElementById('emptyCart');
    if (emptyCartBtn) {
        emptyCartBtn.addEventListener('click', emptyCart);
    }
}

/**
 * Añade un producto al carrito
 */
function addToCart(productId) {
    fetch(`/api/product/${productId}`)
        .then(response => response.json())
        .then(product => {
            let cart = JSON.parse(localStorage.getItem('cart')) || {};
            
            if (cart[productId]) {
                cart[productId].quantity += 1;
            } else {
                cart[productId] = {
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: 1,
                    thc: product.thc_content,
                    cbd: product.cbd_content
                };
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            showToast('Producto añadido al carrito', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error al añadir producto', 'error');
        });
}

/**
 * Actualiza un producto en el carrito
 */
function updateCartItem(productId, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || {};
    
    if (cart[productId]) {
        if (quantity > 0) {
            cart[productId].quantity = quantity;
        } else {
            delete cart[productId];
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        location.reload(); // Recargar para actualizar totales
    }
}

/**
 * Elimina un producto del carrito
 */
function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || {};
    
    if (cart[productId]) {
        delete cart[productId];
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        location.reload();
    }
}

/**
 * Vacía el carrito por completo
 */
function emptyCart() {
    localStorage.removeItem('cart');
    updateCartCount();
    location.reload();
}

/**
 * Actualiza el contador del carrito en el navbar
 */
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    const count = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
    
    document.querySelectorAll('.cart-count').forEach(element => {
        element.textContent = count;
        element.style.display = count > 0 ? 'inline-block' : 'none';
    });
}

/**
 * Inicializa los filtros de productos
 */
function initProductFilters() {
    const strainFilter = document.getElementById('strainFilter');
    const thcFilter = document.getElementById('thcFilter');
    const cbdFilter = document.getElementById('cbdFilter');
    const priceFilter = document.getElementById('priceFilter');
    const filterForm = document.getElementById('productFilters');
    
    if (filterForm) {
        // Aplicar filtros
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyProductFilters();
        });
        
        // Resetear filtros
        document.getElementById('resetFilters').addEventListener('click', function() {
            strainFilter.value = '';
            thcFilter.value = '';
            cbdFilter.value = '';
            priceFilter.value = '';
            applyProductFilters();
        });
    }
    
    // Actualizar etiquetas de rangos
    if (thcFilter) {
        thcFilter.addEventListener('input', function() {
            document.getElementById('thcValue').textContent = this.value + '%';
        });
    }
    
    if (cbdFilter) {
        cbdFilter.addEventListener('input', function() {
            document.getElementById('cbdValue').textContent = this.value + '%';
        });
    }
    
    if (priceFilter) {
        priceFilter.addEventListener('input', function() {
            document.getElementById('priceValue').textContent = '$' + this.value;
        });
    }
}

/**
 * Aplica los filtros de productos
 */
function applyProductFilters() {
    const strain = document.getElementById('strainFilter').value;
    const thc = document.getElementById('thcFilter').value;
    const cbd = document.getElementById('cbdFilter').value;
    const price = document.getElementById('priceFilter').value;
    
    // Ocultar todos los productos
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = 'none';
    });
    
    // Mostrar solo los que coincidan con los filtros
    document.querySelectorAll('.product-card').forEach(card => {
        const cardStrain = card.dataset.strain;
        const cardThc = parseFloat(card.dataset.thc);
        const cardCbd = parseFloat(card.dataset.cbd);
        const cardPrice = parseFloat(card.dataset.price);
        
        const strainMatch = !strain || cardStrain === strain;
        const thcMatch = !thc || cardThc >= parseFloat(thc);
        const cbdMatch = !cbd || cardCbd >= parseFloat(cbd);
        const priceMatch = !price || cardPrice <= parseFloat(price);
        
        if (strainMatch && thcMatch && cbdMatch && priceMatch) {
            card.style.display = 'block';
        }
    });
    
    // Mostrar mensaje si no hay resultados
    const visibleProducts = document.querySelectorAll('.product-card[style="display: block"]');
    const noResults = document.getElementById('noResults');
    
    if (noResults) {
        noResults.style.display = visibleProducts.length === 0 ? 'block' : 'none';
    }
}

/**
 * Inicializa animaciones y efectos
 */
function initAnimations() {
    // Animación de scroll suave
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Efecto hover en tarjetas de producto
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Contador de THC/CBD
    document.querySelectorAll('.thc-meter').forEach(meter => {
        const value = meter.dataset.value;
        meter.style.width = value + '%';
    });
    
    document.querySelectorAll('.cbd-meter').forEach(meter => {
        const value = meter.dataset.value;
        meter.style.width = value + '%';
    });
}

/**
 * Inicializa validaciones de formularios
 */
function initFormValidations() {
    // Validación de registro
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = this.password.value;
            const confirmPassword = this.confirm_password.value;
            const terms = this.terms.checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showToast('Las contraseñas no coinciden', 'error');
                return false;
            }
            
            if (!terms) {
                e.preventDefault();
                showToast('Debes aceptar los términos y condiciones', 'error');
                return false;
            }
            
            return true;
        });
    }
    
    // Validación de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = this.email.value;
            const password = this.password.value;
            
            if (!email || !password) {
                e.preventDefault();
                showToast('Todos los campos son obligatorios', 'error');
                return false;
            }
            
            return true;
        });
    }
}

/**
 * Inicializa el menú móvil
 */
function initMobileMenu() {
    const mobileMenuButton = document.querySelector('.navbar-toggler');
    const mobileMenu = document.querySelector('.navbar-collapse');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
        });
    }
}

/**
 * Inicializa la galería de productos
 */
function initProductGallery() {
    const mainImage = document.querySelector('.product-main-image');
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    
    if (thumbnails.length > 0) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Cambiar imagen principal
                mainImage.src = this.src;
                
                // Remover clase activa de todas las miniaturas
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Añadir clase activa a la miniatura clickeada
                this.classList.add('active');
            });
        });
    }
}

/**
 * Muestra un mensaje toast
 */
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    
    toast.className = `toast show align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Eliminar el toast después de 5 segundos
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

/**
 * Crea el contenedor de toasts si no existe
 */
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '1100';
    document.body.appendChild(container);
    return container;
}

// Inicializar el contador del carrito al cargar la página
updateCartCount();