<?php

    $id = $_POST["id"] ?? NULL;
    $nome = $_POST["nome"] ?? NULL;
    $email = $_POST["email"] ?? NULL;
    $telefone = $_POST["telefone"] ?? NULL;
    $senha = $_POST["senha"] ?? NULL;
    $senha2 = $_POST["senha2"] ?? NULL;
    $ativo = $_POST["ativo"] ?? NULL;


    if (empty($nome)) {
        echo "<script>mensagem('Por favor, preencha o nome do usuário','usuario','error');</script>";
        exit;
    } else if ((empty($id)) && (empty($senha))) {
        echo "<script>mensagem('Por favor, preencha a senha','usuario','error');</script>";
        exit;
    } else if ($senha != $senha2) {
        echo "<script>mensagem('As senhas digitadas não são iguais','usuario','error');</script>";
        exit;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>mensagem('Por favor, preencha com um e-mail válido','usuario','error');</script>";
        exit;
    } else if (empty($ativo)) {
        echo "<script>mensagem('Por favor, selecione a opção ativo','usuario','error');</script>";
        exit;
    } 

    $msg = $this->usuario->salvar();

    if ($msg == 1) {
        $msg = "Registro salvo com sucesso!";
        $tipo = "success";
    } else {
        $msg = "Erro ao alterar/salvar registro";
        $tipo = "error";
    }

    echo "<script>mensagem('{$msg}','usuario/listar','{$tipo}');</script>";
    exit;