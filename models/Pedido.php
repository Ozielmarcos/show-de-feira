<?php

class Pedido {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Salva o pedido e seus itens no banco de dados dentro de uma transação
     */
    public function salvarPedido($usuarioId, $valorTotal, $preferenceId, array $itens) {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO pedido (id, usuario_id, data_pedido, valor_total, status, mercado_pago_preference_id) 
                    VALUES (NULL, :usuario_id, NOW(), :valor_total, 'pendente', :preference_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":usuario_id", $usuarioId);
            $stmt->bindParam(":valor_total", $valorTotal);
            $stmt->bindParam(":preference_id", $preferenceId);
            $stmt->execute();

            $pedidoId = $this->pdo->lastInsertId();

            $sqlItem = "INSERT INTO pedido_item (id, pedido_id, produto_id, quantidade, valor_unitario, subtotal) 
                        VALUES (NULL, :pedido_id, :produto_id, :quantidade, :valor_unitario, :subtotal)";
            $stmtItem = $this->pdo->prepare($sqlItem);

            foreach ($itens as $item) {
                $subtotal = $item['quantidade'] * $item['valor'];
                $stmtItem->bindParam(":pedido_id", $pedidoId);
                $stmtItem->bindParam(":produto_id", $item['id']);
                $stmtItem->bindParam(":quantidade", $item['quantidade']);
                $stmtItem->bindParam(":valor_unitario", $item['valor']);
                $stmtItem->bindParam(":subtotal", $subtotal);
                $stmtItem->execute();
            }

            $this->pdo->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erro ao salvar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarStatus($pedidoId, $status) {
        $sql = "UPDATE pedido SET status = :status WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $pedidoId);
        return $stmt->execute();
    }

    public function getPedido($pedidoId) {
        $sql = "SELECT p.*, u.nome as cliente_nome, u.email as cliente_email 
                FROM pedido p 
                INNER JOIN usuario u ON u.id = p.usuario_id 
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $pedidoId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getItensPedido($pedidoId) {
        $sql = "SELECT i.*, pr.nome as produto_nome, pr.imagem 
                FROM pedido_item i 
                INNER JOIN produto pr ON pr.id = i.produto_id 
                WHERE i.pedido_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $pedidoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
