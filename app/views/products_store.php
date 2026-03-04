<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de productos</title>
    <link rel="stylesheet" href="<?= asset('products.css') ?>">
</head>
<body>
    <header class="store-header">
        <div>
            <p class="badge">Zona Clientes</p>
            <h1>Tienda TechShop</h1>
            <p class="subtitle">Bienvenido, <?= htmlspecialchars($user['name'] ?? $email) ?>. Acceso exclusivo para cuentas de Gmail.</p>
        </div>
        <a class="logout" href="<?= route('dashboard', 'logout') ?>">Cerrar sesión</a>
    </header>

    <main class="catalog">
        <article class="card">
            <h2>Laptop Pro X14</h2>
            <p>Rendimiento premium para trabajo y estudio.</p>
            <span>$1,299</span>
            <button type="button">Agregar al carrito</button>
        </article>

        <article class="card">
            <h2>Audífonos NoiseFree</h2>
            <p>Cancelación de ruido y batería de 30 horas.</p>
            <span>$199</span>
            <button type="button">Agregar al carrito</button>
        </article>

        <article class="card">
            <h2>Mouse Gamer RGB</h2>
            <p>Precisión y ergonomía para largas sesiones.</p>
            <span>$59</span>
            <button type="button">Agregar al carrito</button>
        </article>

        <article class="card">
            <h2>Monitor UltraWide 34"</h2>
            <p>Más espacio visual para productividad total.</p>
            <span>$499</span>
            <button type="button">Agregar al carrito</button>
        </article>
    </main>
</body>
</html>
