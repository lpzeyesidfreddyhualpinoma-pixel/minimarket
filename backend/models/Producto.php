<?php
class Producto {
    private $conn;
    private $table_name = "Producto";

    public $id;
    public $nombre;
    public $proveedor;
    public $fecha_vencimiento;
    public $precio;
    public $stock;
    public $eliminado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Consultar únicamente productos activos (eliminado = 0)
    public function obtenerActivos() {
        $query = "SELECT id, nombre, proveedor, fecha_vencimiento, precio, stock, eliminado 
                  FROM " . $this->table_name . " 
                  WHERE eliminado = 0 ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. Registrar nuevo producto
    public function crear() {
        if ($this->precio < 0 || $this->stock < 0) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, proveedor, fecha_vencimiento, precio, stock, eliminado) 
                  VALUES (:nombre, :proveedor, :fecha_vencimiento, :precio, :stock, 0)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", htmlspecialchars(strip_tags($this->nombre)));
        $stmt->bindParam(":proveedor", htmlspecialchars(strip_tags($this->proveedor)));
        $stmt->bindParam(":fecha_vencimiento", $this->fecha_vencimiento);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);

        return $stmt->execute();
    }

    // 3. Editar información de un producto
    public function actualizar() {
        if ($this->precio < 0 || $this->stock < 0) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, proveedor = :proveedor, 
                      fecha_vencimiento = :fecha_vencimiento, precio = :precio, stock = :stock 
                  WHERE id = :id AND eliminado = 0";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nombre", htmlspecialchars(strip_tags($this->nombre)));
        $stmt->bindParam(":proveedor", htmlspecialchars(strip_tags($this->proveedor)));
        $stmt->bindParam(":fecha_vencimiento", $this->fecha_vencimiento);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);

        return $stmt->execute();
    }

    // 4. Eliminar lógicamente (cambiar estado a eliminado = 1)
    public function eliminarLogico() {
        $query = "UPDATE " . $this->table_name . " 
                  SET eliminado = 1 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Obtener un solo producto por ID
    public function obtenerPorId() {
        $query = "SELECT id, nombre, proveedor, fecha_vencimiento, precio, stock, eliminado 
                  FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>