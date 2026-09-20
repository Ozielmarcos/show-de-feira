<?php

    $id = $_POST["id"] ?? NULL;
    $nome = $_POST["nome"] ?? NULL;
    $categoria_id = $_POST["categoria_id"] ?? NULL;
    $descricao = $_POST["descricao"] ?? NULL;
    $imagem = $_POST["imagem"] ?? NULL;
    $valor = $_POST["valor"] ?? NULL;
    $destaque = $_POST["destaque"] ?? NULL;
    $ativo = $_POST["ativo"] ?? NULL;

    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);

    $_POST["valor"] = $valor;

    if ((empty($id) && (empty($_FILES["imagem"]["name"])))) {
        echo "<script>mensagem('Por favor, selecione uma imagem','produto','error');</script>";
        exit;
    } else if (empty($nome)) {
        echo "<script>mensagem('Por favor, preencha o nome do produto','produto','error');</script>";
        exit;
    } else if (empty($descricao)) {
        echo "<script>mensagem('Por favor, preencha a descrição do produto','produto','error');</script>";
        exit;
    } else if (empty($ativo)) {
        echo "<script>mensagem('Por favor, selecione a opção ativo','produto','error');</script>";
        exit;
    } 

    if (!empty($_FILES["imagem"]["name"])) {
        $_POST["imagem"] = md5($nome) . time() . ".jpg";
    }

    $msg = $this->produto->salvar();

    if ($msg == 1) {
        $msg = "Registro salvo com sucesso!";
        $tipo = "success";
        if (!empty($_FILES["imagem"]["name"])) {
            if (!is_dir("arquivos")) {
                @mkdir("arquivos", 0777, true);
            }
            move_uploaded_file($_FILES["imagem"]["tmp_name"], "arquivos/".$_POST["imagem"]);
        }
    } else {
        $msg = "Erro ao alterar/salvar registro";
        $tipo = "error";
    }

    echo "<script>mensagem('{$msg}','produto/listar','{$tipo}');</script>";
    exit;