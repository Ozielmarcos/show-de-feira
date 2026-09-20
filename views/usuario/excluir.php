<?php

    if (empty($id)) {
        echo "<script>mensagem('Registro inválido','usuario/listar','error');</script>";
        exit;
    }

    $msg = $this->usuario->excluir($id);

    if ($msg == 1) {
        $msg = "Registro inativado com sucesso!";
        $tipo = "success";
    } else {
        $msg = "Erro ao excluir/inativar registro";
        $tipo = "error";
    }

    echo "<script>mensagem('{$msg}','usuario/listar','{$tipo}');</script>";
    exit;
