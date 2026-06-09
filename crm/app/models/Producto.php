<?php
class Producto {
    // devuelve todas las secciones ordenadas
    public static function secciones($pdo) {
        return $pdo->query("SELECT * FROM secciones ORDER BY orden")->fetchAll();
    }
    
    // busca una sección por su slug
    public static function seccion($pdo, $slug) {
        $stmt = $pdo->prepare("SELECT * FROM secciones WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $resultado = $stmt->fetch();

        if ($resultado) {
            return $resultado;
        }

        return null;
    }

    // devuelve los productos de una sección con su precio promocional si tiene
    public static function porSeccion($pdo, $idSeccion) {
        $sql = "SELECT p.*, pr.id AS id_promo, pr.precio_promocional
                FROM productos p
                LEFT JOIN promociones pr ON pr.id_producto = p.id
                    AND pr.activa = 1
                    AND CURDATE() BETWEEN pr.fecha_inicio AND pr.fecha_fin
                WHERE p.id_seccion = ? AND p.disponible = 1
                ORDER BY p.nombre";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idSeccion]);
        return $stmt->fetchAll();
    }

    // devuelve todos los productos con el nombre de su sección
    public static function todos($pdo) {
        $sql = "SELECT p.*, s.nombre AS seccion_nombre, s.slug AS seccion_slug
                FROM productos p
                JOIN secciones s ON p.id_seccion = s.id
                ORDER BY s.orden, p.nombre";
                
        return $pdo->query($sql)->fetchAll();
    }

    // busca un producto por id
    public static function porId($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();

        if ($resultado) {
            return $resultado;
        }

        return null;
    }

    // crea un producto nuevo
    public static function crear($pdo, $datos) {
        $sql = "INSERT INTO productos (id_seccion, nombre, descripcion, precio, foto, disponible, stock)
                VALUES (:id_seccion, :nombre, :descripcion, :precio, :foto, :disponible, :stock)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($datos);
    }

    // actualiza los datos de un producto
    public static function actualizar($pdo, $id, $datos) {
        $sql = "UPDATE productos
                SET id_seccion=:id_seccion, nombre=:nombre, descripcion=:descripcion,
                    precio=:precio, foto=:foto, disponible=:disponible, stock=:stock
                WHERE id=:id";

        $datos['id'] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($datos);
    }

    // elimina un producto por id
    public static function eliminar($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
    }
}
