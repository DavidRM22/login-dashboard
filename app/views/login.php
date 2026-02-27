<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= asset('estilos.css') ?>">
</head>
<body>
<div class="page-shell">
    <section class="panel">
        <h1>Bienvenido de vuelta</h1>
        <p class="subtitle">Ingresa tus credenciales para continuar.</p>

        <form class="form-grid" method="POST" action="<?= route('auth', 'doLogin') ?>">
            <div class="field">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" placeholder="usuario@empresa.com" required>
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" placeholder="••••••••" required>
            </div>

            <button class="btn-primary" type="submit">Iniciar sesión</button>
        </form>

        <div class="links-row">
            <a href="<?= route('auth', 'register') ?>">¿No tienes cuenta? Regístrate</a>
        </div>
    </section>
</div>
</body>
</html>
