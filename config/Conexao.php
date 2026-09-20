<?php

class Conexao {
    private static $host = "mysql";
    private static $usuario = "root";
    private static $senha = "root";
    private static $banco = "showfeira";

    public static function conectar() {
        $host = getenv('DB_HOST') ?: self::$host;
        $banco = getenv('DB_DATABASE') ?: self::$banco;
        $usuario = getenv('DB_USER') ?: self::$usuario;
        $senha = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : self::$senha;

        try {
            $pdo = new PDO(
                "mysql:host={$host};dbname={$banco};charset=utf8mb4",
                $usuario,
                $senha,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            die("Erro ao conectar: {$e->getMessage()}");
        }
    }
}