<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/database.php';
include_once '../models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $producto->id = $_GET['id'];
            $res = $producto->obtenerPorId();
            if ($res) {
                http_response_code(200);
                echo json_encode($res);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado."]);
            }
        } else {
            $stmt = $producto->obtenerActivos();
            $num = $stmt->rowCount();
            $productos_arr = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $item = [
                    "id" => $id,
                    "nombre" => $nombre,
                    "proveedor" => $proveedor,
                    "fecha_vencimiento" => $fecha_vencimiento,
                    "precio" => (float)$precio,
                    "stock" => (int)$stock,
                    "eliminado" => (int)$eliminado
                ];
                array_push($productos_arr, $item);
            }
            http_response_code(200);
            echo json_encode($productos_arr);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->proveedor) && !empty($data->fecha_vencimiento) && isset($data->precio) && isset($data->stock)) {
            $producto->nombre = $data->nombre;
            $producto->proveedor = $data->proveedor;
            $producto->fecha_vencimiento = $data->fecha_vencimiento;
            $producto->precio = $data->precio;
            $producto->stock = $data->stock;

            if ($producto->crear()) {
                http_response_code(201);
                echo json_encode(["message" => "Producto creado con éxito."]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "No se pudo crear el producto. Verifique los datos."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id) && !empty($data->nombre) && !empty($data->proveedor) && !empty($data->fecha_vencimiento) && isset($data->precio) && isset($data->stock)) {
            $producto->id = $data->id;
            $producto->nombre = $data->nombre;
            $producto->proveedor = $data->proveedor;
            $producto->fecha_vencimiento = $data->fecha_vencimiento;
            $producto->precio = $data->precio;
            $producto->stock = $data->stock;

            if ($producto->actualizar()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto actualizado con éxito."]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "No se pudo actualizar el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos para actualizar."]);
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            $data = json_decode(file_get_contents("php://input"));
            $id = isset($data->id) ? $data->id : null;
        }

        if ($id) {
            $producto->id = $id;
            if ($producto->eliminarLogico()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto eliminado lógicamente con éxito."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo eliminar el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID de producto no proporcionado."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido."]);
        break;
}
?>