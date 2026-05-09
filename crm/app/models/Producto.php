<?php
class Producto {
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
}
