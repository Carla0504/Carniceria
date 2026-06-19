<?php

class CarritoItem {

    // saco los items con nombre, sección y precio de promo si tiene
    public static function porUsuario($pdo, $idUsuario) {
        $sql = "SELECT ci.id, ci.cantidad,
                       p.id AS id_producto, p.nombre, p.precio, p.unidad_medida,
                       s.nombre AS seccion_nombre,
                       pr.precio_promocional
                FROM carrito_items ci
                JOIN productos p ON ci.id_producto = p.id
                JOIN secciones s ON p.id_seccion = s.id
                LEFT JOIN promociones pr ON pr.id_producto = p.id
                    AND pr.activa = 1
                    AND CURDATE() BETWEEN pr.fecha_inicio AND pr.fecha_fin
                WHERE ci.id_usuario = ?
                ORDER BY ci.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idUsuario]);
        $items = $stmt->fetchAll();

        // si el producto tiene promoción activa uso ese precio, si no el normal
        foreach ($items as &$item) {
            if ($item['precio_promocional'] != null) {
                $item['precio_efectivo'] = $item['precio_promocional'];
            } else {
                $item['precio_efectivo'] = $item['precio'];
            }
        }

        return $items;
    }

    // cuenta cuántos productos distintos hay en el carrito (para el badge del menú)
    public static function contar($pdo, $idUsuario) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM carrito_items WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        return (int) $stmt->fetchColumn();
    }

    // añade un producto al carrito; el incremento depende de la unidad de medida
    public static function agregar($pdo, $idUsuario, $idProducto, $incremento = 1) {
        $incremento = max(0.001, (float)$incremento);
        $sql = "INSERT INTO carrito_items (id_usuario, id_producto, cantidad)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idUsuario, $idProducto, $incremento, $incremento]);
    }

    // actualiza la cantidad de un producto concreto del carrito
    public static function actualizar($pdo, $id, $idUsuario, $cantidad) {
        $stmt = $pdo->prepare("UPDATE carrito_items SET cantidad = ? WHERE id = ? AND id_usuario = ?");
        $stmt->execute([$cantidad, $id, $idUsuario]);
    }

    // elimina un producto del carrito
    public static function eliminar($pdo, $id, $idUsuario) {
        $stmt = $pdo->prepare("DELETE FROM carrito_items WHERE id = ? AND id_usuario = ?");
        $stmt->execute([$id, $idUsuario]);
    }

    // vacía el carrito entero del usuario
    public static function vaciar($pdo, $idUsuario) {
        $stmt = $pdo->prepare("DELETE FROM carrito_items WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
    }
}
