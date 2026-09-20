<?php

$nome = $email = $telefone = $ativo = NULL;

if (!empty($id)) {
    $dados = $this->usuario->editar($id);

    if (empty($dados)) {
        echo "<script>mensagem('Registro não encontrado!','usuario','error');</script>";
        exit;
    }

    $id = $dados->id;
    $nome = $dados->nome;
    $email = $dados->email;
    $telefone = $dados->telefone;
    $ativo = $dados->ativo;
}

?>
<div class="card">
    <div class="card-header">
        <h2 class="float-start">Cadastro de Usuários</h2>
        <div class="float-end">
            <a href="usuario" title="Novo Registro" class="btn btn-success">
                <i class="fas fa-file"></i> Novo Registro
            </a>

            <a href="usuario/listar" title="Listar" class="btn btn-success">
                <i class="fas fa-file"></i> Listar
            </a>
        </div>
    </div>
    <div class="card-body">
        <form name="formCadastro" method="post" action="usuario/salvar" data-parsley-validate="">
            <div class="row">
                <div class="col-12 col-md-1">
                    <label for="id">ID:</label>
                    <input type="text" name="id" id="id" class="form-control" readonly value="<?= $id ?>">
                </div>
                <div class="col-12 col-md-6">
                    <label for="nome">Nome do Usuário:</label>
                    <input type="text" name="nome" id="nome" required class="form-control" value="<?= $nome ?>"
                        data-parsley-required-message="Preencha o nome">
                </div>
                <div class="col-12 col-md-5">
                    <label for="email">E-mail do Usuário:</label>
                    <input type="email" name="email" id="email" required class="form-control" value="<?= $email ?>"
                        data-parsley-required-message="Preencha o e-mail"
                        data-parsley-type-message="Digite um e-mail válido">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 col-md-4">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" class="form-control"
                        >
                </div>
                <div class="col-12 col-md-4">
                    <label for="senha2">Redigite a senha:</label>
                    <input type="password" name="senha2" id="senha2"  class="form-control"
                        data-parsley-equalto="#senha"
                        data-parsley-equalto-message="As senhas são diferentes">
                </div>
                <div class="col-12 col-md-2">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" id="telefone" required class="form-control"
                        data-parsley-required-message="Preencha o telefone" value="<?= $telefone ?>">
                    <script>
                        $("#telefone").inputmask("(99) 99999-9999");
                    </script>
                </div>
                <div class="col-12 col-md-2">
                    <label for="ativo">Ativo:</label>
                    <select name="ativo" id="ativo" class="form-select" required data-parsley-required-message="Selecione uma opção">
                        <option value="">Selecione</option>
                        <option value="S">Sim</option>
                        <option value="N">Não</option>
                    </select>

                    <script>
                        $("#ativo").val('<?= $ativo ?>');
                    </script>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-success float-end">
                <i class="fas fa-check"></i> Salvar/Atualizar Registro
            </button>
        </form>
    </div>
</div>