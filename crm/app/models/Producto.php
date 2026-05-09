<?php
class Producto {
    public static function secciones(PDO $pdo): array {
        return $pdo->query("SELECT * FROM secciones ORDER BY orden")->fetchAll();
    }

    public static function seccion(PDO $pdo, string $slug): ?array {
        $stmt = $pdo->prepare("SELECT * FROM secciones WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public static function porSeccion(PDO $pdo, int $idSeccion): array {
        $stmt = $pdo->prepare(
            "SELECT p.*, pr.precio_promocional
             FROM productos p
             LEFT JOIN promociones pr ON pr.id_producto = p.id
                 AND pr.activa = 1
                 AND CURDATE() BETWEEN pr.fecha_inicio AND pr.fecha_fin
             WHERE p.id_seccion = ? AND p.disponible = 1
             ORDER BY p.nombre"
        );
        $stmt->execute([$idSeccion]);
        return $stmt->fetchAll();
    }

    public static function todos(PDO $pdo): array {
        return $pdo->query(
            "SELECT p.*, s.nombre AS seccion_nombre, s.slug AS seccion_slug
             FROM productos p
             JOIN secciones s ON p.id_seccion = s.id
             ORDER BY s.orden, p.nombre"
        )->fetchAll();
    }

    public static function porId(PDO $pdo, int $id): ?array {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function crear(PDO $pdo, array $datos): void {
        $stmt = $pdo->prepare(
            "INSERT INTO productos (id_seccion, nombre, descripcion, precio, foto, disponible)
             VALUES (:id_seccion, :nombre, :descripcion, :precio, :foto, :disponible)"
        );
        $stmt->execute($datos);
    }

    public static function actualizar(PDO $pdo, int $id, array $datos): void {
        $stmt = $pdo->prepare(
            "UPDATE productos
             SET id_seccion=:id_seccion, nombre=:nombre, descripcion=:descripcion,
                 precio=:precio, foto=:foto, disponible=:disponible
             WHERE id=:id"
        );
        $stmt->execute(array_merge($datos, ['id' => $id]));
    }

    public static function eliminar(PDO $pdo, int $id): void {
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
    }
}
