<?php
    require_once "../config/Conexao.php";
    require_once "../models/Produto.php";

    class ProdutoController {

        private $produto;

        public function __construct()
        {
            $db = new Conexao();
            $pdo = $db->conectar();
            $this->produto = new Produto($pdo);
        }

        public function index($id = null) {
            require "../views/produto/index.php";
        }

        public function excluir($id = null) {
            require "../views/produto/excluir.php";
        }

        public function salvar() {
            require "../views/produto/salvar.php";
        }

        public function listar() {
            require "../views/produto/listar.php";
        }

        public function detalhes($id = null) {
            require "../views/produto/detalhes.php";
        }
    }