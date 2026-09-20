<?php
    require_once "../config/Conexao.php";
    require_once "../models/Usuario.php";

    class UsuarioController {

        private $usuario;

        public function __construct()
        {
            $db = new Conexao();
            $pdo = $db->conectar();
            $this->usuario = new Usuario($pdo);
        }

        public function index($id = null) {
            require "../views/usuario/index.php";
        }

        public function excluir($id = null) {
            require "../views/usuario/excluir.php";
        }

        public function salvar() {
            require "../views/usuario/salvar.php";
        }

        public function listar() {
            require "../views/usuario/listar.php";
        }
    }