<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?= asset('estilos.css') ?>">
    <script src="<?= asset('theme.js') ?>" defer></script>
</head>
<body>
<button class="theme-toggle" type="button" data-theme-toggle aria-label="Cambiar tema"></button>
<div class="page-shell">
    <section class="panel">
        <h1>Crea tu cuenta</h1>
        <p class="subtitle">Regístrate para iniciar sesión y continuar.</p>

        <?php if (!empty($_SESSION['register_error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['register_error']) ?></div>
            <?php unset($_SESSION['register_error']); ?>
        <?php endif; ?>

        <form class="form-grid" method="POST" action="<?= route('auth', 'doRegister') ?>">
            <div class="field">
                <label for="name">Nombre completo</label>
                <input id="name" type="text" name="name" placeholder="Tu nombre" required>
            </div>

            <div class="field">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" placeholder="usuario@gmail.com" required>
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="field">
                <label for="confirm_password">Confirmar contraseña</label>
                <input id="confirm_password" type="password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button class="btn-primary" type="submit">Registrarme</button>
        </form>

        <div class="links-row">
            <a href="<?= route('auth', 'login') ?>">Ya tengo cuenta, iniciar sesión</a>
        </div>
    </section>
</div>
</body>
</html>
