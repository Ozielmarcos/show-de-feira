<?php
    class Categoria {

        private $pdo;
        public $id;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function editar($id) {
            $sql = "select * from categoria where id = :id limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":id", $id);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_OBJ);
        }

        public function listar() {
            $sql = "select * from categoria order by descricao";
            $consulta = $this->pdo->prepare($sql);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_OBJ);
        }

        public function salvar() {
    

            if (empty($_POST["id"])) {
                $sql = "insert into categoria (id, descricao, ativo) values (NULL, :descricao, :ativo)";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":descricao", $_POST["descricao"]);
                $consulta->bindParam(":ativo", $_POST["ativo"]);
            } else {
                $sql = "update categoria set descricao = :descricao, ativo = :ativo where id = :id limit 1";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":descricao", $_POST["descricao"]);
                $consulta->bindParam(":ativo", $_POST["ativo"]);
                $consulta->bindParam(":id", $_POST["id"]);
            }

            return $consulta->execute();
        }

        public function excluir($id) {
            $sql = "delete from categoria where id = :id limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":id", $id);

            return $consulta->execute();
        }
    }