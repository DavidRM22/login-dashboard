<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RRHH</title>
    <link rel="stylesheet" href="<?= asset('estilos.css') ?>">
    <link rel="stylesheet" href="<?= asset('dashboard.css') ?>">
    <script src="<?= asset('theme.js') ?>" defer></script>
</head>
<body class="dashboard-body">
<button class="theme-toggle" type="button" data-theme-toggle aria-label="Cambiar tema"></button>
<?php
$view = $_GET['view'] ?? 'table';
$activeView = in_array($view, ['table', 'gallery'], true) ? $view : 'table';
$section = $_GET['section'] ?? 'rrhh';
$activeSection = in_array($section, ['rrhh', 'ventas'], true) ? $section : 'rrhh';
$salesTab = $_GET['sales_tab'] ?? 'ventas';
$activeSalesTab = in_array($salesTab, ['ventas', 'auditoria'], true) ? $salesTab : 'ventas';

$salesRows = [
    ['id' => 'VTA-001', 'cliente' => 'Carlos Mendoza', 'producto' => 'Audifono Honor X6B', 'monto' => 'S/ 85,00', 'pago' => 'Crédito', 'estado' => 'Completada', 'fecha' => '2026-03-04'],
];

$salesSearch = trim($_GET['sales_search'] ?? '');
$salesStatus = trim($_GET['sales_status'] ?? '');
$salesPayment = trim($_GET['sales_payment'] ?? '');

$filteredSalesRows = [];
foreach ($salesRows as $row) {
    if ($salesSearch !== '') {
        $needle = mb_strtolower($salesSearch);
        $found = false;
        foreach (['id', 'cliente', 'producto'] as $field) {
            if (mb_strpos(mb_strtolower($row[$field]), $needle) !== false) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            continue;
        }
    }

    if ($salesStatus !== '' && $row['estado'] !== $salesStatus) {
        continue;
    }

    if ($salesPayment !== '' && $row['pago'] !== $salesPayment) {
        continue;
    }

    $filteredSalesRows[] = $row;
}
?>
<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <h3>Dashboard</h3>
        <p>Panel de Administración</p>

        <span class="sidebar-section">PANEL</span>
        <a class="sidebar-link <?= $activeSection === 'rrhh' ? 'active' : '' ?>" href="<?= route('dashboard', 'index') ?>">Inicio</a>
        <a class="sidebar-link <?= $activeSection === 'ventas' ? 'active' : '' ?>" href="<?= route('dashboard', 'index') ?>&section=ventas">Ventas</a>
        <a class="sidebar-link" href="<?= route('dashboard', 'audit') ?>">Auditoría</a>

        <span class="sidebar-section">GENERAL</span>
        <a class="sidebar-link" href="<?= route('dashboard', 'addEmployee') ?>">Agregar empleado</a>
        <a class="sidebar-link" href="<?= route('dashboard', 'logout') ?>">Cerrar sesión</a>
    </aside>

    <main class="dashboard-main">
        <?php if ($activeSection === 'ventas'): ?>
            <section class="panel panel--wide hr-panel hr-panel--fullscreen sales-panel">
                <h1>Ventas</h1>

                <nav class="tab-row sales-tab-row">
                    <a class="tab-item <?= $activeSalesTab === 'ventas' ? 'active' : '' ?>" href="<?= route('dashboard', 'index') ?>&section=ventas&sales_tab=ventas">Ventas</a>
                    <a class="tab-item <?= $activeSalesTab === 'auditoria' ? 'active' : '' ?>" href="<?= route('dashboard', 'index') ?>&section=ventas&sales_tab=auditoria">Auditoría de ventas</a>
                </nav>

                <?php if ($activeSalesTab === 'ventas'): ?>
                    <form class="filter-row sales-filter-row" method="get" action="<?= route('dashboard', 'index') ?>" aria-label="Filtros de ventas">
                        <input type="hidden" name="controller" value="dashboard">
                        <input type="hidden" name="action" value="index">
                        <input type="hidden" name="section" value="ventas">
                        <input type="hidden" name="sales_tab" value="ventas">
                        <input type="search" name="sales_search" value="<?= htmlspecialchars($salesSearch) ?>" placeholder="Buscar venta..." aria-label="Buscar venta">

                        <select name="sales_status" aria-label="Filtrar por estado">
                            <option value="">Estado</option>
                            <option value="Completada" <?= $salesStatus === 'Completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="Pendiente" <?= $salesStatus === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="En revisión" <?= $salesStatus === 'En revisión' ? 'selected' : '' ?>>En revisión</option>
                        </select>

                        <select name="sales_payment" aria-label="Filtrar por pago">
                            <option value="">Pago</option>
                            <option value="Contado" <?= $salesPayment === 'Contado' ? 'selected' : '' ?>>Contado</option>
                            <option value="Crédito" <?= $salesPayment === 'Crédito' ? 'selected' : '' ?>>Crédito</option>
                            <option value="Transferencia" <?= $salesPayment === 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        </select>

                        <button class="btn-secondary" type="submit">Filtrar</button>
                    </form>

                    <div class="table-wrap table-wrap--dashboard">
                        <table class="audit-table dashboard-table sales-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <th>Monto</th>
                                <th>Pago</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($filteredSalesRows as $sale): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sale['id']) ?></td>
                                    <td><?= htmlspecialchars($sale['cliente']) ?></td>
                                    <td><?= htmlspecialchars($sale['producto']) ?></td>
                                    <td><?= htmlspecialchars($sale['monto']) ?></td>
                                    <td><?= htmlspecialchars($sale['pago']) ?></td>
                                    <td><span class="status-chip sales-status sales-status--<?= strtolower(str_replace(' ', '-', $sale['estado'])) ?>"><?= htmlspecialchars($sale['estado']) ?></span></td>
                                    <td><?= htmlspecialchars($sale['fecha']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($filteredSalesRows)): ?>
                                <tr>
                                    <td colspan="7">No se encontraron ventas con los filtros seleccionados.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="table-wrap table-wrap--dashboard">
                        <table class="audit-table dashboard-table sales-table">
                            <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Usuario</th>
                                <th>Detalle</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Edición de venta</td>
                                <td>admin@empresa.com</td>
                                <td>Se actualizó el estado de VTA-003 a Pendiente.</td>
                                <td>2026-03-04 09:42</td>
                            </tr>
                            <tr>
                                <td>Anulación</td>
                                <td>supervisor@empresa.com</td>
                                <td>Se anuló el cobro de la venta VTA-004 para revisión.</td>
                                <td>2026-03-03 15:10</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        <?php else: ?>
        <section class="panel panel--wide hr-panel hr-panel--fullscreen">
            <h1>Formulario Tabla Detallada</h1>

            <div class="module-headline">
                <h2>Gestión Interna (RRHH & Ops)</h2>
                <p>Recursos humanos, soporte al cliente y comunidad</p>
            </div>

            <nav class="tab-row">
                <a class="tab-item active" href="<?= route('dashboard', 'index') ?>">Recursos Humanos</a>
                <a class="tab-item" href="#">Soporte</a>
                <a class="tab-item" href="#">Comunidad</a>
            </nav>

            <div class="subtab-row">
                <a class="subtab-item active" href="<?= route('dashboard', 'index') ?>">Personal</a>
                <a class="subtab-item" href="<?= route('dashboard', 'index') ?>">Desempeño</a>
                <a class="subtab-item" href="<?= route('dashboard', 'index') ?>">Objetivos</a>
                <a class="subtab-item" href="<?= route('dashboard', 'audit') ?>">Auditoría</a>
            </div>

            <div class="module-header">
                <div>
                    <h3>Recursos Humanos</h3>
                    <p class="subtitle">Gestión de personal y empleados</p>
                </div>

                <div class="module-actions">
                    <a class="btn-secondary btn-inline" href="<?= route('dashboard', 'audit') ?>">Exportar</a>
                    <a class="btn-primary btn-inline" href="<?= route('dashboard', 'addEmployee') ?>">Agregar Empleado</a>
                </div>
            </div>
            <?php
            // calcular estadísticas por tipo de empleado
            $rowsForStats = $allUsers ?? (isset($user) ? [$user] : []);
            $totalPersonal = count($rowsForStats);
            $counts = [];
            foreach ($rowsForStats as $r) {
                $t = trim($r['type'] ?? '');
                if ($t === '') $t = 'Otro';
                if (!isset($counts[$t])) $counts[$t] = 0;
                $counts[$t]++;
            }

            $instructores = $counts['Instructor'] ?? 0;
            $desarrolladores = $counts['Desarrollador'] ?? 0;
            $administradores = $counts['Administrador'] ?? 0;
            $asistAdministrativos = $counts['Asistente Administrativo'] ?? 0;
            ?>

            <div class="stats-grid">
                <article class="stat-card">
                    <h4>Total Personal</h4>
                    <p class="stat-value"><?= $totalPersonal ?></p>
                    <small>Empleados registrados</small>
                </article>
                <article class="stat-card">
                    <h4>Instructores</h4>
                    <p class="stat-value"><?= $instructores ?></p>
                    <small>Equipo docente</small>
                </article>
                <article class="stat-card">
                    <h4>Desarrolladores</h4>
                    <p class="stat-value"><?= $desarrolladores ?></p>
                    <small>Equipo técnico</small>
                </article>
                <article class="stat-card">
                    <h4>Administradores</h4>
                    <p class="stat-value"><?= $administradores ?></p>
                    <small>Personal administrativo</small>
                </article>
                <article class="stat-card">
                    <h4>Asist. Administrativos</h4>
                    <p class="stat-value"><?= $asistAdministrativos ?></p>
                    <small>Personal de soporte</small>
                </article>
            </div>

            <div class="subtab-row media-tabs">
                <a class="subtab-item <?= $activeView === 'gallery' ? 'active' : '' ?>" href="<?= route('dashboard', 'index') ?>&view=gallery">Galería de Fotos</a>
                <a class="subtab-item <?= $activeView === 'table' ? 'active' : '' ?>" href="<?= route('dashboard', 'index') ?>&view=table">Tabla Detallada</a>
            </div>

            <form class="filter-row" method="get" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" aria-label="Filtros de búsqueda">
                <input type="hidden" name="controller" value="dashboard">
                <input type="hidden" name="action" value="index">
                <input
                    type="search"
                    name="search"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    placeholder="Buscar por nombre, email o puesto..."
                    aria-label="Buscar personal">

                <select name="type" aria-label="Filtrar por tipo" onchange="this.form.submit()">
                    <option value="" <?= (isset($_GET['type']) && $_GET['type'] === '') || !isset($_GET['type']) ? 'selected' : '' ?>>Todos los tipos</option>
                    <option value="Instructor" <?= (isset($_GET['type']) && $_GET['type'] === 'Instructor') ? 'selected' : '' ?>>Instructor</option>
                    <option value="Desarrollador" <?= (isset($_GET['type']) && $_GET['type'] === 'Desarrollador') ? 'selected' : '' ?>>Desarrollador</option>
                    <option value="Administrador" <?= (isset($_GET['type']) && $_GET['type'] === 'Administrador') ? 'selected' : '' ?>>Administrador</option>
                    <option value="Asistente Administrativo" <?= (isset($_GET['type']) && $_GET['type'] === 'Asistente Administrativo') ? 'selected' : '' ?>>Asistente Administrativo</option>
                </select>

                <select name="status" aria-label="Filtrar por estado" onchange="this.form.submit()">
                    <option value="" <?= (isset($_GET['status']) && $_GET['status'] === '') || !isset($_GET['status']) ? 'selected' : '' ?>>Todos los estados</option>
                    <option value="Activo" <?= (isset($_GET['status']) && $_GET['status'] === 'Activo') ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= (isset($_GET['status']) && $_GET['status'] === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                </select>

                <button class="btn-secondary" type="submit">Aplicar</button>
            </form>

            <?php if ($activeView === 'gallery'): ?>
                <div class="gallery-grid">
                    <?php
                    $galleryRows = $allUsers ?? [];
                    if (empty($galleryRows)) {
                        $galleryRows = isset($user) ? [$user] : [];
                    }
                    ?>
                    <?php foreach ($galleryRows as $u): ?>
                        <?php
                        $name = trim($u['name'] ?? '');
                        $initial = strtoupper(substr($name !== '' ? $name : 'N', 0, 1));
                        $photoUrl = trim($u['photo_url'] ?? '');
                        ?>
                        <article class="profile-card">
                            <?php if ($photoUrl !== ''): ?>
                                <div class="avatar avatar--image">
                                    <img src="<?= asset($photoUrl) ?>" alt="Foto de <?= htmlspecialchars($name) ?>">
                                </div>
                            <?php else: ?>
                                <div class="avatar"><?= htmlspecialchars($initial) ?></div>
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($name) ?></h4>
                            <span class="role-chip"><?= htmlspecialchars($u['type'] ?? 'Sin tipo') ?></span>
                            <p><?= htmlspecialchars($u['email'] ?? '') ?></p>
                            <small>Registrado: <?= htmlspecialchars($u['created_at'] ?? '-') ?></small>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="table-wrap table-wrap--dashboard">
                    <table class="audit-table dashboard-table">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Puesto</th>
                            <th>Departamento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rows = $allUsers ?? [];
                        if (empty($rows)) :
                            // fallback a mostrar usuario actual
                            $rows = isset($user) ? [$user] : [];
                        endif;

                        foreach ($rows as $u) :
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($u['name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                <td><span class="role-chip"><?= htmlspecialchars($u['type'] ?? '') ?></span></td>
                                <td><?= htmlspecialchars($u['position'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['department'] ?? '') ?></td>
                                <td><span class="status-chip"><?= htmlspecialchars($u['status'] ?? '') ?></span></td>
                                <td>
                                    <a class="table-link" href="<?= route('dashboard', 'viewEmployee') ?>&id=<?= urlencode($u['id'] ?? '') ?>">Ver</a>
                                    <a class="table-link" href="<?= route('dashboard', 'editEmployee') ?>&id=<?= urlencode($u['id'] ?? '') ?>">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
