<?php

    if (empty($id)) {
        echo "<script>mensagem('Registro inválido','categoria/listar','error');</script>";
        exit;
    }

    try {
        $msg = $this->categoria->excluir($id);

        if ($msg == 1) {
            $msg = "Registro excluído com sucesso!";
            $tipo = "success";
        } else {
            $msg = "Erro ao excluir registro";
            $tipo = "error";
        }
    } catch (PDOException $e) {
        $msg = "Não é possível excluir esta categoria porque ela possui produtos vinculados!";
        $tipo = "error";
    }

    echo "<script>mensagem('{$msg}','categoria/listar','{$tipo}');</script>";
    exit;