CREATE DATABASE IF NOT EXISTS db_minimarket_pro413;
USE db_minimarket_pro413;

CREATE TABLE IF NOT EXISTS Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    proveedor VARCHAR(100) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    precio DECIMAL(10,2) NOT NULL CHECK (precio >= 0),
    stock INT NOT NULL CHECK (stock >= 0),
    eliminado TINYINT(1) NOT NULL DEFAULT 0
);

-- Insertar datos de prueba iniciales
INSERT INTO Producto (nombre, proveedor, fecha_vencimiento, precio, stock, eliminado) VALUES
('Leche Entera 1L', 'Pil Andina', '2026-12-31', 7.50, 50, 0),
('Pan Molde Familiar', 'Bimbo', '2026-11-15', 14.00, 30, 0),
('Aceite Vegetal 1L', 'Fino', '2027-05-20', 12.50, 20, 0);