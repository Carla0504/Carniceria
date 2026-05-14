<?php
class Promocion {
    // devuelve la promoción de un producto (haya o no haya activa)
    public static function porProducto($pdo, $idProducto) {
        $stmt = $pdo->prepare("SELECT * FROM promociones WHERE id_producto = ? LIMIT 1");
        $stmt->execute([$idProducto]);
        $resultado = $stmt->fetch();

        if ($resultado) {
            return $resultado;
        }

        return null;
    }

    // crea una promoción nueva para un producto
    public static function crear($pdo, $datos) {
        $sql = "INSERT INTO promociones (id_producto, precio_promocional, activa, fecha_inicio, fecha_fin)
                VALUES (:id_producto, :precio_promocional, 1, :fecha_inicio, :fecha_fin)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($datos);
    }

    // actualiza el precio y las fechas de una promoción existente
    public static function actualizar($pdo, $id, $datos) {
        $sql = "UPDATE promociones
                SET precio_promocional=:precio_promocional,
                    fecha_inicio=:fecha_inicio,
                    fecha_fin=:fecha_fin
                WHERE id=:id";

        $datos['id'] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($datos);
    }

    // elimina una promoción por id
    public static function eliminar($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM promociones WHERE id = ?");
        $stmt->execute([$id]);
    }
}
