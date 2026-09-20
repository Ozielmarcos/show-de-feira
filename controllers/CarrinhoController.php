<?php
require_once "../config/Conexao.php";
require_once "../models/Produto.php";
require_once "../models/Pedido.php";
require_once "../config/MercadoPagoService.php";

class CarrinhoController {

    private $produto;
    private $pedido;

    public function __construct() {
        $db = new Conexao();
        $pdo = $db->conectar();
        $this->produto = new Produto($pdo);
        $this->pedido = new Pedido($pdo);

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public function index() {
        require "../views/carrinho/index.php";
    }

    public function adicionar($id = null) {
        $produtoId = $id ?: ($_POST['produto_id'] ?? null);
        $quantidade = max(1, (int)($_POST['quantidade'] ?? 1));
        $comprarAgora = !empty($_POST['comprar_agora']);

        if (empty($produtoId)) {
            echo "<script>mensagem('Produto inválido!','index','error');</script>";
            exit;
        }

        $dados = $this->produto->getProdutoDetalhes($produtoId);
        if (empty($dados)) {
            echo "<script>mensagem('Produto não encontrado ou inativo!','index','error');</script>";
            exit;
        }

        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId]['quantidade'] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produtoId] = [
                'id' => $dados->id,
                'nome' => $dados->nome,
                'categoria' => $dados->categoria_nome ?? '',
                'valor' => (float)$dados->valor,
                'imagem' => $dados->imagem,
                'quantidade' => $quantidade,
            ];
        }

        // Atualiza subtotal do item
        $_SESSION['carrinho'][$produtoId]['subtotal'] = 
            $_SESSION['carrinho'][$produtoId]['quantidade'] * $_SESSION['carrinho'][$produtoId]['valor'];

        if ($comprarAgora) {
            echo "<script>location.href='carrinho/finalizar';</script>";
        } else {
            echo "<script>location.href='carrinho';</script>";
        }
        exit;
    }

    public function atualizar() {
        if (!empty($_POST['quantidades']) && is_array($_POST['quantidades'])) {
            foreach ($_POST['quantidades'] as $prodId => $qtd) {
                $qtd = (int)$qtd;
                if ($qtd <= 0) {
                    unset($_SESSION['carrinho'][$prodId]);
                } else if (isset($_SESSION['carrinho'][$prodId])) {
                    $_SESSION['carrinho'][$prodId]['quantidade'] = $qtd;
                    $_SESSION['carrinho'][$prodId]['subtotal'] = $qtd * $_SESSION['carrinho'][$prodId]['valor'];
                }
            }
        }
        echo "<script>location.href='carrinho';</script>";
        exit;
    }

    public function excluir($id = null) {
        if (!empty($id) && isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
        echo "<script>location.href='carrinho';</script>";
        exit;
    }

    public function limpar() {
        $_SESSION['carrinho'] = [];
        echo "<script>location.href='carrinho';</script>";
        exit;
    }

    public function finalizar() {
        if (empty($_SESSION['carrinho'])) {
            echo "<script>mensagem('Seu carrinho está vazio!','index','info');</script>";
            exit;
        }

        // Se o cliente não estiver logado, redireciona para login e guarda rota de retorno
        if (empty($_SESSION['feira']['id'])) {
            $_SESSION['redirect_after_login'] = 'carrinho/finalizar';
            echo "<script>mensagem('Por favor, faça login para continuar sua compra!','index','info');</script>";
            exit;
        }

        // Calcula total geral do carrinho
        $total = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['valor'] * $item['quantidade'];
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
        $baseUrl = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';

        // Cria pedido inicial no banco de dados
        $pedidoId = $this->pedido->salvarPedido($_SESSION['feira']['id'], $total, null, $_SESSION['carrinho']);

        if (!$pedidoId) {
            echo "<script>mensagem('Erro ao processar seu pedido. Tente novamente!','carrinho','error');</script>";
            exit;
        }

        // Gera a preferência no Mercado Pago Checkout Pro
        $preferencia = MercadoPagoService::criarPreferencia(
            $_SESSION['carrinho'],
            $_SESSION['feira'],
            $pedidoId,
            $baseUrl
        );

        if (!empty($preferencia['id'])) {
            $this->pedido->atualizarStatus($pedidoId, 'aguardando_pagamento');
        }

        require "../views/carrinho/finalizar.php";
    }

    /**
     * Tela de retorno de sucesso do Mercado Pago
     */
    public function sucesso() {
        $pedidoId = $_GET['pedido_id'] ?? ($_GET['external_reference'] ?? null);
        if ($pedidoId) {
            $this->pedido->atualizarStatus($pedidoId, 'aprovado');
        }
        // Esvazia carrinho após sucesso
        $_SESSION['carrinho'] = [];
        require "../views/carrinho/sucesso.php";
    }

    /**
     * Tela de retorno de falha/cancelamento do Mercado Pago
     */
    public function falha() {
        echo "<script>mensagem('O pagamento não foi concluído. Você pode tentar novamente!','carrinho','error');</script>";
        exit;
    }

    /**
     * Tela de retorno de pagamento pendente (Boleto/Pix)
     */
    public function pendente() {
        $pedidoId = $_GET['pedido_id'] ?? ($_GET['external_reference'] ?? null);
        if ($pedidoId) {
            $this->pedido->atualizarStatus($pedidoId, 'pendente');
        }
        $_SESSION['carrinho'] = [];
        require "../views/carrinho/sucesso.php";
    }
}
