<?php
    require_once "../config/Conexao.php";
    require_once "../models/Usuario.php";
    require_once "../models/Produto.php";

    class IndexController {

        private $usuario;
        private $produto;

        public function __construct()
        {
            $db = new Conexao();
            $pdo = $db->conectar();
            $this->usuario = new Usuario($pdo);
            $this->produto = new Produto($pdo);
        }

        public function index() {
            require_once "../views/index/home.php";
        }

        public function verificar($email, $senha) {
            $dados = $this->usuario->getUsuario($email);

            if (!$dados || !password_verify($senha, $dados->senha)) {
                echo "<script>mensagem('E-mail ou senha incorretos','index','error');</script>";
                exit;
            }

            if ($dados->ativo !== 'S') {
                echo "<script>mensagem('Usuário inativo no sistema','index','error');</script>";
                exit;
            }

            $_SESSION["feira"] = array("id" => $dados->id, "nome" => $dados->nome, "email" => $dados->email);
            $redirect = $_SESSION['redirect_after_login'] ?? 'index';
            unset($_SESSION['redirect_after_login']);
            echo "<script>location.href='{$redirect}';</script>";
            exit;
        }

        public function login() {
            if (isset($_SESSION["feira"]["id"])) {
                echo "<script>location.href='index';</script>";
                exit;
            }
            require_once "../views/index/index.php";
        }

        public function sair() {
            session_destroy();
            echo "<script>location.href='index';</script>";
        }
    }