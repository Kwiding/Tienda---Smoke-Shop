<nav class="navbar navbar-expand-lg navbar-dark bg-success navbar-custom py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="/assets/images/logo.png" alt="GreenLeaf Logo" height="40" class="me-2">
            <span class="fw-bold">GreenLeaf</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                        Productos
                    </a>
                    <ul class="dropdown-menu">
                        <?php 
                        $categories = (new Category($db))->getAll();
                        foreach ($categories as $category): 
                        ?>
                            <li>
                                <a class="dropdown-item" href="/category/<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/products">Todos los productos</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Sobre Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contacto</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center">
                <a href="/cart" class="btn btn-outline-light position-relative me-3">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo array_reduce($_SESSION['cart'], fn($carry, $item) => $carry + $item['quantity'], 0); ?>
                        </span>
                    <?php endif; ?>
                </a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i> <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="/orders">Mis Pedidos</a></li>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/admin">Panel Admin</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="views/auth/login.php" class="btn btn-outline-light">
                        <i class="fas fa-sign-in-alt me-1"></i> Ingresar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>