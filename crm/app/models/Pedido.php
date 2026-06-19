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
}