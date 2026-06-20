<?php
class Pedido {
    public static function crear($pdo, $idUsuario, $items, $total) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (?, ?)");
            $stmt->execute([$idUsuario, $total]);
            $idPedido = (int)$pdo->lastInsertId();

            $stmtItem = $pdo->prepare(
                "INSERT INTO pedido_items 
                    (id_pedido, id_producto, nombre_producto, precio_unitario, cantidad, unidad_medida, subtotal)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");

            foreach ($items as $item) {
                $subtotal = round($item['precio_efectivo'] * $item['cantidad'], 2);
                $stmtItem->execute([
                    $idPedido,
                    $item['id_producto'],
                    $item['nombre'],
                    $item['precio_efectivo'],
                    $item['cantidad'],
                    $item['unidad_medida'],
                    $subtotal,
                ]);
                $stmtStock->execute([$item['cantidad'], $item['id_producto']]);
            }

            $pdo->commit();
            return $idPedido;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function todos($pdo) {
        return $pdo->query(
            "SELECT p.*, u.nombre AS cliente_nombre, u.email AS cliente_email
             FROM pedidos p
             JOIN usuarios u ON p.id_usuario = u.id
             ORDER BY p.created_at DESC"
        )->fetchAll();
    }

    public static function porUsuario($pdo, $idUsuario) {
        $stmt = $pdo->prepare(
            "SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public static function items($pdo, $idPedido) {
        $stmt = $pdo->prepare("SELECT * FROM pedido_items WHERE id_pedido = ?");
        $stmt->execute([$idPedido]);
        return $stmt->fetchAll();
    }
}