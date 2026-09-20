<?php

    if (empty($id)) {
        echo "<script>mensagem('Registro inválido','produto/listar','error');</script>";
        exit;
    }

    $msg = $this->produto->excluir($id);

    if ($msg == 1) {
        $msg = "Registro excluído com sucesso!";
        $tipo = "success";
    } else {
        $msg = "Erro ao excluir registro";
        $tipo = "error";
    }

    echo "<script>mensagem('{$msg}','produto/listar','{$tipo}');</script>";
    exit;