<?php
require_once 'api_client.php';
$productos = callAPI('GET', API_BASE_URL);

// Comprovar si s'ha rebut un missatge d'error des del backend
$error_msg = null;
if (isset($productos['message'])) {
    $error_msg = $productos['message'];
    $productos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos - Minimarket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h1 class="mb-4">Gestión de Productos - Minimarket</h1>
    <a href="crear.php" class="btn btn-primary mb-3">Registrar Nuevo Producto</a>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger mb-3">
            <strong>Error de conexión / API:</strong> <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Proveedor</th>
                <th>Fecha Vencimiento</th>
                <th>Precio (bs)</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos) && is_array($productos) && isset($productos[0])): ?>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['proveedor']) ?></td>
                        <td><?= htmlspecialchars($p['fecha_vencimiento']) ?></td>
                        <td>Bs<?= number_format($p['precio'], 2) ?></td>
                        <td><?= htmlspecialchars($p['stock']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar lógicamente este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay productos activos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>