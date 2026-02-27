<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría del Sistema</title>
    <link rel="stylesheet" href="<?= asset('estilos.css') ?>">
</head>
<body>
<div class="page-shell">
    <section class="panel panel--wide">
        <h1>Auditoría del sistema</h1>
        <p class="subtitle">Registro de eventos y actividad reciente.</p>

        <div class="table-wrap">
            <table class="audit-table">
                <thead>
                <tr>
                    <th>Evento</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Fecha</th>
                    <th>Detalles</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log['event'] ?></td>
                        <td><?= $log['email'] ?></td>
                        <td><?= $log['ip'] ?></td>
                        <td><?= $log['created_at'] ?></td>
                        <td><?= $log['details'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="links-row">
            <a href="<?= route('dashboard', 'index') ?>">Volver al panel</a>
        </div>
    </section>
</div>
</body>
</html>
