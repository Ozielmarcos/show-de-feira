<?php

$descricao = $ativo = NULL;

if (!empty($id)) {
    $dados = $this->categoria->editar($id);

    if (empty($dados)) {
        echo "<script>mensagem('Registro não encontrado!','categoria','error');</script>";
        exit;
    }

    $id = $dados->id;
    $descricao = $dados->descricao;
    $ativo = $dados->ativo;
}

?>
<div class="card">
    <div class="card-header">
        <h2 class="float-start">Cadastro de Categorias</h2>
        <div class="float-end">
            <a href="categoria" title="Novo Registro" class="btn btn-success">
                <i class="fas fa-file"></i> Novo Registro
            </a>

            <a href="categoria/listar" title="Listar" class="btn btn-success">
                <i class="fas fa-file"></i> Listar
            </a>
        </div>
    </div>
    <div class="card-body">
        <form name="formCadastro" method="post" action="categoria/salvar" data-parsley-validate="">
            <div class="row">
                <div class="col-12 col-md-1">
                    <label for="id">ID:</label>
                    <input type="text" name="id" id="id" class="form-control" readonly value="<?= $id ?>">
                </div>
                <div class="col-12 col-md-9">
                    <label for="descricao">Categoria:</label>
                    <input type="text" name="descricao" id="descricao" required class="form-control" value="<?= $descricao ?>"
                        data-parsley-required-message="Preencha a categoria">
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