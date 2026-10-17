<?php
require_once 'api_client.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = [
        'nombre' => $_POST['nombre'],
        'proveedor' => $_POST['proveedor'],
        'fecha_vencimiento' => $_POST['fecha_vencimiento'],
        'precio' => (float)$_POST['precio'],
        'stock' => (int)$_POST['stock']
    ];
    
    $res = callAPI('POST', API_BASE_URL, $payload);
    
    // Si la respuesta no es falsa ni contiene un error explícito, fue exitoso
    if ($res !== false && !isset($res['error'])) {
        header('Location: index.php');
        exit();
    } else {
        $mensaje = 'Error al registrar el producto.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Registrar Producto</h2>
    <?php if ($mensaje): ?><div class="alert alert-danger"><?= $mensaje ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required maxlength="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Proveedor</label>
            <input type="text" name="proveedor" class="form-control" required maxlength="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" name="fecha_vencimiento" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" name="precio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" min="0" name="stock" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>