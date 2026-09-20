<?php
    require_once "../config/Conexao.php";
    require_once "../models/Categoria.php";

    class CategoriaController {

        private $categoria;

        public function __construct()
        {
            $db = new Conexao();
            $pdo = $db->conectar();
            $this->categoria = new Categoria($pdo);
        }

        public function index($id = null) {
            require "../views/categoria/index.php";
        }

        public function excluir($id = null) {
            require "../views/categoria/excluir.php";
        }

        public function salvar() {
            require "../views/categoria/salvar.php";
        }

        public function listar() {
            require "../views/categoria/listar.php";
        }
    }