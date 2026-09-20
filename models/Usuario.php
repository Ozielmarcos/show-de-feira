<?php

    class Usuario {
        private $pdo;

        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function getUsuario($email) {

            $sql = "select * from usuario where email = :email limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":email", $email);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_OBJ);

        }

        public function editar($id) {
            $sql = "select * from usuario where id = :id limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":id", $id);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_OBJ);
        }

        public function listar() {
            $sql = "select * from usuario order by nome";
            $consulta = $this->pdo->prepare($sql);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_OBJ);
        }

        public function salvar() {
            
            if (!empty($_POST["senha"])) $_POST["senha"] = password_hash($_POST["senha"], PASSWORD_DEFAULT);

            if (empty($_POST["id"])) {
                $sql = "insert into usuario (id, nome, email, senha, telefone, ativo) 
                values (NULL, :nome, :email, :senha, :telefone, :ativo)";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":nome", $_POST["nome"]);
                $consulta->bindParam(":email", $_POST["email"]);
                $consulta->bindParam(":senha", $_POST["senha"]);
                $consulta->bindParam(":telefone", $_POST["telefone"]);
                $consulta->bindParam(":ativo", $_POST["ativo"]);

            } else if (empty($_POST["senha"])) {
                $sql = "update usuario set nome = :nome, email = :email, telefone = :telefone, ativo = :ativo where id = :id limit 1";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":nome", $_POST["nome"]);
                $consulta->bindParam(":email", $_POST["email"]);
                $consulta->bindParam(":telefone", $_POST["telefone"]);
                $consulta->bindParam(":id", $_POST["id"]);
                $consulta->bindParam(":ativo", $_POST["ativo"]);

            } else {
                $sql = "update usuario set senha = :senha, nome = :nome, email = :email, telefone = :telefone, ativo = :ativo where id = :id limit 1";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":nome", $_POST["nome"]);
                $consulta->bindParam(":email", $_POST["email"]);
                $consulta->bindParam(":senha", $_POST["senha"]);
                $consulta->bindParam(":telefone", $_POST["telefone"]);
                $consulta->bindParam(":id", $_POST["id"]);
                $consulta->bindParam(":ativo", $_POST["ativo"]);
            }

            return $consulta->execute();
        }

        public function excluir($id) {
            $sql = "update usuario set ativo = 'N'where id = :id limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":id", $id);

            return $consulta->execute();
        }

        public function getLogin($email) {

            $sql = "select id, nome, senha, ativo from usuario where email = :email and ativo = 'S' limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":email", $email);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_OBJ);
        }
    }