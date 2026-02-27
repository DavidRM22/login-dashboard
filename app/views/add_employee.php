<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar nuevo empleado</title>
    <link rel="stylesheet" href="<?= asset('estilos.css') ?>">
    <link rel="stylesheet" href="<?= asset('dashboard.css') ?>">
</head>
<body class="dashboard-body">
<div class="employee-shell">
    <section class="employee-modal">
        <div class="employee-modal__header">
            <h1>Agregar Nuevo Empleado</h1>
            <a class="employee-modal__close" href="<?= route('dashboard', 'index') ?>" aria-label="Cerrar">×</a>
        </div>

        <form class="employee-form" method="get" action="<?= route('dashboard', 'index') ?>">
            <h2>Información del Usuario</h2>
            <div class="employee-grid employee-grid--two">
                <div class="field">
                    <label for="full_name">Nombre Completo *</label>
                    <input id="full_name" name="full_name" type="text" placeholder="Ej: Juan Pérez García" required>
                </div>

                <div class="field">
                    <label for="email">Correo Electrónico *</label>
                    <input id="email" name="email" type="email" placeholder="juan.perez@empresa.com" required>
                </div>

                <div class="field">
                    <label for="phone">Teléfono</label>
                    <input id="phone" name="phone" type="text" placeholder="+51 999 999 999">
                </div>
            </div>

            <h2>Foto de Perfil</h2>
            <div class="photo-row">
                <div class="photo-placeholder">📷</div>
                <div>
                    <button class="btn-secondary btn-inline" type="button">Capturar Foto con Reconocimiento Facial</button>
                    <p>Opcional: Captura la foto del empleado con verificación biométrica</p>
                </div>
            </div>

            <h2>Información Laboral</h2>
            <div class="employee-grid employee-grid--two">
                <div class="field">
                    <label for="type">Tipo de Empleado *</label>
                    <select id="type" name="type" required>
                        <option>Administrador</option>
                        <option>Instructor</option>
                        <option>Desarrollador</option>
                        <option>Asistente Administrativo</option>
                    </select>
                </div>

                <div class="field">
                    <label for="department">Departamento</label>
                    <select id="department" name="department">
                        <option>Seleccionar departamento</option>
                        <option>Administracion</option>
                        <option>Recursos Humanos</option>
                        <option>Tecnologia de la Informacion</option>
                        <option>Desarrollo</option>
                        <option>Educacion</option>
                        <option>Marketing</option>
                        <option>Ventas</option>
                        <option>Soporte Tecnico</option>
                        <option>Operaciones</option>
                        <option>Finanzas</option>
                    </select>
                </div>

                <div class="field">
                    <label for="position">Puesto / Posición</label>
                    <select id="position" name="position">
                        <option>Seleccionar puesto</option>
                        <option>Instructor Senior</option>
                        <option>Instructor Junior</option>
                        <option>Coordinador de Contenidos</option>
                        <option>Especialista en Formacion</option>
                    
                    </select>
                </div>

                <div class="field">
                    <label for="hired_at">Fecha de Contratación</label>
                    <input id="hired_at" name="hired_at" type="date">
                </div>

                <div class="field">
                    <label for="status">Estado *</label>
                    <select id="status" name="status" required>
                        <option>Activo</option>
                        <option>Inactivo</option>
                    </select>
                </div>
            </div>

            <h2>Información Profesional</h2>

            <div class="employee-actions">
                <a class="btn-secondary btn-inline" href="<?= route('dashboard', 'index') ?>">Cancelar</a>
                <button class="btn-primary btn-inline" type="submit">Agregar Empleado</button>
            </div>
        </form>
    </section>
</div>
</body>
</html>
