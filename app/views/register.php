<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?= asset('estilos.css') ?>">
</head>
<body>
<div class="page-shell">
    <section class="panel">
        <h1>Crear cuenta</h1>
        <p class="subtitle">Completa tus datos para registrarte en el sistema.</p>

        <form class="form-grid" method="POST" action="<?= route('auth', 'doRegister') ?>">
            <div class="field">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" placeholder="Tu nombre" required>
            </div>

            <div class="field">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" placeholder="usuario@empresa.com" required>
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" placeholder="Crea una contraseña" required>
            </div>

            <button class="btn-primary" type="submit">Registrarse</button>
        </form>

        <div class="links-row">
            <a href="<?= route('auth', 'login') ?>">Volver al login</a>
        </div>
    </section>
</div>
</body>
</html>
