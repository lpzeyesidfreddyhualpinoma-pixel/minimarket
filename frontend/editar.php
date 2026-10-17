<?php
require_once 'api_client.php';
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = [
        'id' => (int)$_POST['id'],
        'nombre' => $_POST['nombre'],
        'proveedor' => $_POST['proveedor'],
        'fecha_vencimiento' => $_POST['fecha_vencimiento'],
        'precio' => (float)$_POST['precio'],
        'stock' => (int)$_POST['stock']
    ];
    callAPI('PUT', API_BASE_URL, $payload);
    header('Location: index.php');
    exit();
}

$producto = callAPI('GET', API_BASE_URL . '?id=' . $id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Editar Producto #<?= htmlspecialchars($producto['id']) ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Proveedor</label>
            <input type="text" name="proveedor" class="form-control" value="<?= htmlspecialchars($producto['proveedor']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" name="fecha_vencimiento" class="form-control" value="<?= htmlspecialchars($producto['fecha_vencimiento']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" name="precio" class="form-control" value="<?= htmlspecialchars($producto['precio']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" min="0" name="stock" class="form-control" value="<?= htmlspecialchars($producto['stock']) ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>